<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Role;

class RolesController extends FrontEndBaseController
{
    public function index()
    {
        if (auth()->user()->isNotAllowTo('list-role')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $roleTree = $this->buildRoleTree($this->authUserOwnRoleId());

        // excluding own role:
        $childrenRoles = $roleTree['children'] ?? [];

        $data = [
            'roleTree'      => json_encode($childrenRoles),
            'ownProvileges' => array_merge(['Own provileges: ' . count(auth()->user()->privileges)], auth()->user()->privileges),
            'seo'           => [
                'pageTitle'       => 'Roles',
            ],
        ];

        return view('role.index', $data);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-role')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $roleName = $request->name;
        $parentId = $request->parentId;

        $validator = \Validator::make(
            [
                'name'     => $roleName,
                'parentId' => $parentId,
            ],
            [
                'name'     => 'required|slug',
                'parentId' => 'required|positive',
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $error) {
                $messages[] = $error;
            }

            return redirect()->back()->with('error', implode(', ', $messages));
        } else {

            $role = new Role;
            $role->name = $roleName;
            $role->save();

            if (!empty($role->id)) {
                $child = new \App\RoleChildren;

                $child->role_id = $parentId;
                $child->child_id = $role->id;

                $child->save();

                \App\User::setAllResetCredentials();

                return redirect()->back()->with('success', 'Role successfully created.');
            } else {
                return redirect()->back()->with('error', 'Failed while creating new role.');
            }
        }
    }

    private function _updateRoleTree($item, $rootKey = false) // recursive
    {
        if (auth()->user()->isNotAllowTo('update-role')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if ($rootKey) {
            \App\RoleChildren::where('role_id', $rootKey)->delete();

            $rootChildren = [];
            $rootData = [];
        }
        if (!empty($item)) {
            foreach ($item as $node) {
                if ($rootKey) {
                    $rootChildren[] = $node->key;
                    $rootData[] = [
                        'role_id'  => $rootKey,
                        'child_id' => $node->key,
                    ];
                }

                \App\RoleChildren::where('role_id', $node->key)->delete();

                $childrendIds = [];
                $data = [];

                if (!empty($node->children)) {
                    foreach ($node->children as $child) {
                        $childrendIds[] = $child->key;
                        $data[] = [
                            'role_id'  => $node->key,
                            'child_id' => $child->key,
                        ];
                    }
                    \DB::table('role_children')->insert($data);
                }

                if (!empty($node->children)) {
                    $this->_updateRoleTree($node->children);
                }
            }
            if ($rootKey) {
                \DB::table('role_children')->insert($rootData);
            }
        }

        \App\User::setAllResetCredentials();
    }

    public function ajaxGetRolePrivileges(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-privilege')) {
            $result = [
                'success' => false,
                'error'   => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {

            $input = $request->all();

            if ($roleId = $input['roleId']) {
                $ownPrivilegesIds = $this->getRoleOwnPrivilegesIds($roleId);
                $ownAndAvailablePrivilegesIds = array_merge($ownPrivilegesIds, $this->getRoleAvailablePrivilegesIds($roleId));

                $rows = \App\Privilege::ordered()->whereIn('id', $ownAndAvailablePrivilegesIds)->get();

                $ownAndAvailablePrivileges = [];
                foreach ($rows as $row) {
                    $ownAndAvailablePrivileges[] = [
                        'value'   => $row->id,
                        'content' => $row->name,
                    ];
                }

                $result = [
                    'success'                   => true,
                    // ids:
                    'ownAndAvailablePrivileges' => $ownAndAvailablePrivileges,
                    'ownPrivilegesIds'          => $ownPrivilegesIds,
                    // names:
                    'inheritedPrivileges'       => $this->getInheritedPrivileges($roleId),
                ];
            } else {
                $result = ['success' => false, 'error' => 'There is no info available.'];
            }
        } else {
            $result = ['success' => false, 'error' => 'Invalid request'];
        }

        return response()->json($result);
    }

    public function ajaxUpdateRolePrivileges(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-privilege')) {
            $result = [
                'success' => false,
                'error'   => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {

            $input = $request->all();

            if ($roleId = $input['roleId']) {
                $privilegesIds = explode('|', $input['privilegesStrCid']);

                $role = Role::find($roleId);

                $role->privileges()->sync($privilegesIds);

                \App\User::setAllResetCredentials();

                $result = [
                    'success' => true,
                ];
            } else {
                $result = ['success' => false, 'error' => 'There is no roleId defined.'];
            }
        } else {
            $result = ['success' => false, 'error' => 'Invalid request'];
        }

        return response()->json($result);
    }

    public function ajaxUpdateRoleTree(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-role')) {
            $result = [
                'success' => false,
                'error'   => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {

            $roleTree = json_decode($request->roleTree);

            if (!empty($roleTree)) {
                $this->_updateRoleTree($roleTree, 1);

                $result = [
                    'success' => true,
                ];
            } else {
                $result = ['success' => false, 'error' => 'There is no info posted.'];
            }
        } else {
            $result = ['success' => false, 'error' => 'Invalid request'];
        }

        return response()->json($result);
    }

    public function ajaxUpdateRoleName(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-role')) {
            $result = [
                'success' => false,
                'error'   => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {

            $roleId = $request->roleId;
            $roleName = $request->roleName;

            $validator = \Validator::make(
                [
                    'id'   => $roleId,
                    'name' => $roleName,
                ],
                [
                    'id'   => 'required|positive',
                    'name' => 'required|slug|unique:roles',
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                foreach ($errors->all() as $error) {
                    $messages[] = $error;
                }

                $result = ['success' => false, 'error' => implode(', ', $messages)];
            } else {
                $role = Role::find($roleId);

                if (!empty($role->id)) {
                    $role->name = $roleName;
                    $role->save();

                    \App\User::setAllResetCredentials();

                    $result = [
                        'success' => true,
                    ];
                } else {
                    $result = ['success' => false, 'error' => 'Record not found.'];
                }
            }
        } else {
            $result = ['success' => false, 'error' => 'Invalid request'];
        }

        return response()->json($result);
    }

    public function ajaxRemoveRole(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-role')) {
            $result = [
                'success' => false,
                'error'   => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {


            if ($role = Role::find($request->roleId)) {

                $role->delete();

                \App\User::setAllResetCredentials();

                $result = [
                    'success' => true,
                ];
            } else {
                $result = ['success' => false, 'error' => 'Role not found.'];
            }
        } else {
            $result = ['success' => false, 'error' => 'Invalid request'];
        }

        return response()->json($result);
    }

}
