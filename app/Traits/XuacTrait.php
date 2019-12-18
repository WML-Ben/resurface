<?php

// 2017-09-25 - Added function userHasRoles to get roles from any user

trait XuacTrait
{

    /*******************************   Roles   *********************************/

    /** public ROLE functions: */

    // alias function
    public function roleIs($roleName)
    {
        return $this->authUserRoleIs($roleName);
    }

    // alias function
    public function hasRole($roleName)
    {
        return $this->authUserHasRole($roleName);
    }
    
    // alias function
    public function hasRoles($roleNames)
    {
        return $this->authUserHasRoles($roleNames);
    }

    // alias function
    public function ownRoleId()
    {
        return $this->authUserOwnRoleId();
    }

    // alias function
    public function ownRole()
    {
        return $this->authUserOwnRole();
    }

    // alias function
    public function roleIds($roleName)
    {
        return $this->authUserRoleIds($roleName);
    }

    public function authUserHasRole($roleName)
    {
        return !empty(auth()->user()) ? $this->authUserOwnRole() == 'root' || in_array($this->getRoleIdFromName($roleName), $this->authUserRoleIds()) : null;
    }
    
    public function authUserHasRoleId($roleId)
    {
        return !empty(auth()->user()) && in_array($roleId, $this->authUserRoleIds());
    }
    
    public function authUserHasRoles($roles = [])
    {
        return (boolean)(count(array_intersect($this->roleNamesBelow(auth()->user()->role->id, null, true), $roles)));
    }
    
    // get roles from any user (passing the role id):

    public function userHasRoles($roleId, $roles = [])
    {
        return (boolean)(count(array_intersect($this->roleNamesBelow($roleId, null, true), $roles)));
    }

    public function authUserRoleIs($roleName)
    {
        return !empty(auth()->user()) ? strtolower($this->authUserOwnRole()) == strtolower($roleName) : null;
    }

    public function authUserOwnRoleId()
    {
        return !empty(auth()->user()) ? auth()->user()->role_id : null;
    }

    public function authUserOwnRole()
    {
        return !empty(auth()->user()) ? auth()->user()->role->name : null;
    }

    public function authUserRoleIds()
    {
        return !empty(auth()->user()) ? $this->roleIdsBelow(auth()->user()->role_id) : null;
    }

    /** utility ROLE functions: */

    public function roleIdsBelow($roleId, $includeOwn = true)
    {
        $roles = $includeOwn ? ["$roleId"] : [];

        return $this->getRolesIdsBelow($roleId, $roles);
    }

    public function rolesBelow($roleId = false, $default = [], $includeOwn = false)
    {
        $roles = $this->rolesBelowCB($roleId, $default, $includeOwn);

        return array_values($roles);
    }

    public function rolesOwnAndBelow($roleId = false, $default = [])
    {
        $roles = $this->rolesBelowCB($roleId, $default, true);

        return array_values($roles);
    }

    public function rolesBelowCB($roleId = false, $default = [], $includeOwn = true)
    {
        if (empty($roleId)) {
            $roleId = $this->ownRoleId();
        }
        $roles = $this->roleNamesBelow($roleId, $default, $includeOwn);

        asort($roles);

        return $roles;
    }

    /** protected ROLE functions: */

    protected function roleNamesBelow($roleId, $default = [], $includeOwn = false)
    {
        $roles = $default;

        if ($includeOwn) {
            $roles[$roleId] = $this->getRoleNameFromId($roleId);
        }

        return $this->getRoleNamesBelow($roleId, $roles);
    }

    protected function roleNamesBelowNum($roleId, $default = [], $includeOwn = false)
    {
        $roles = $default;

        if ($includeOwn) {
            $roles[] = $this->getRoleNameFromId($roleId);
        }

        return $this->getRoleNamesBelow($roleId, $roles);
    }

    protected function getRoleIdFromName($roleName)
    {
        return \App\Role::where('name', $roleName)->value('id');
    }

    protected function getRoleNameFromId($roleId)
    {
        return \App\Role::where('id', $roleId)->value('name');
    }

    protected function fetchNodeInfo($roleId)
    {
        $data = [
            'key'   => $roleId,
            'title' => $this->getRoleNameFromId($roleId),
        ];

        if ($children = $this->getChildrenRolesTree($roleId)) {
            $data['children'] = $children;
            foreach ($data['children'] as & $child) {
                $child = $this->fetchNodeInfo($child['key']);
            }
        }

        return $data;
    }

    function buildRoleTree($roleId, $includeOwn = false)
    {
        return $this->fetchNodeInfo($roleId);
    }


    protected function getChildrenRolesTree($roleId)
    {
        // return as fancytree source array sctructure:

        return \App\RoleChildren::select('roles.id AS key', 'roles.name AS title')
            ->join('roles', 'role_children.child_id', '=', 'roles.id')
            ->where('roles.disabled', 0)
            ->where('role_children.role_id', $roleId)
            ->orderBy('roles.name')
            ->get()
            ->ToArray();
    }

    protected function getRolesIdsBelow($roleId, $rolesPool = [])
    {
        if ((!$parentRole = \App\Role::active()->find($roleId)) || (!$children = $parentRole->children())) {
            return $rolesPool;
        }

        if ($roles = $children->pluck('child_id')->all()) {
            $rolesPool = array_merge($rolesPool, $roles);

            // get roles below recursively
            foreach ($roles as $roleId) {
                $rolesPool = $this->getRolesIdsBelow($roleId, $rolesPool);
            }
        }

        return $rolesPool;
    }

    protected function getRoleNamesBelow($roleId, $rolesPool = [])
    {
        $roles = \App\RoleChildren::join('roles', 'role_children.child_id', '=', 'roles.id')
            ->where('roles.disabled', 0)
            ->where('role_children.role_id', $roleId)
            ->orderBy('roles.name')
            ->pluck('roles.name', 'roles.id')->all();

        if ($roles) {
            $rolesPool = (array)$rolesPool + $roles;

            // get roles below recursively
            foreach ($roles as $roleId => $roleName) {
                $rolesPool = $this->getRoleNamesBelow($roleId, $rolesPool);
            }
        }

        return $rolesPool;
    }


    /*******************************   Privileges   *********************************/

    /** public PRIVILEGE functions: */

    // alias function
    public function hasPrivilege($privilegeName)
    {
        return $this->authUserHasPrivilege($privilegeName);
    }
    
    // alias function
    public function hasPrivileges($privilegeNames)
    {
        return $this->authUserHasPrivileges($privilegeNames);
    }

    // alias function
    public function ownPrivileges()
    {
        return $this->authUserOwnPrivileges();
    }

    public function authUserHasPrivilege($privilegeName)
    {
        return !empty(auth()->user()) ? $this->authUserOwnRole() == 'root' || in_array($this->getPrivilegeIdFromName(strtolower($privilegeName)), $this->authUserPrivilegeIds()) : null;
    }
    
    public function authUserHasPrivileges($privileges = [])
    {
        return (boolean)(count(array_intersect($this->getRoleOwnAndInheritedPrivileges(auth()->user()->role->id), $privileges)));
    }

    public function authUserOwnPrivileges()
    {
        return !empty(auth()->user()) ? auth()->user()->privileges : null;
    }

    public function authUserPrivileges()
    {
        $privileges = [];

        if ($roles = $this->authUserRoleIds()) {
            foreach ($roles as $roleId) {
                $privileges = array_merge($privileges, $this->getRoleOwnPrivileges($roleId));
            }
        }

        asort($privileges);

        return $privileges;
    }

    public function authUserPrivilegeIds()
    {
        return !empty(auth()->user()) ? $this->getRoleOwnAndInheritedPrivilegesIds(auth()->user()->role_id) : null;
    }

    /** utility PRIVILEGE functions: */

    public function getRolesIdsBelowPrivileges($roleId)
    {
        $privileges = [];

        if ($roles = $this->getRolesIdsBelow($roleId)) {
            foreach ($roles as $roleId) {
                $privileges = array_merge($privileges, $this->getRoleOwnPrivileges($roleId));
            }
        }

        return $privileges;
    }

    public function getRolesIdsBelowPrivilegesIds($roleId)
    {
        $privilegesIds = [];

        if ($roles = $this->getRolesIdsBelow($roleId)) {
            foreach ($roles as $roleId) {
                $privilegesIds = array_merge($privilegesIds, $this->getRoleOwnPrivilegesIds($roleId));
            }
        }

        return $privilegesIds;// getInheritedPrivileges  getRoleOwnPrivileges getAllPrivileges
    }

    // all privileges:

    public function getAllPrivileges()
    {
        return \App\Privilege::ordered()->pluck('name')->all();
    }

    public function getAllPrivilegesIds()
    {
        return \App\Privilege::pluck('id')->all();
    }

    // inherited privileges:

    public function getInheritedPrivileges($roleId)
    {
        return array_diff($this->getRolesIdsBelowPrivileges($roleId), $this->getRoleOwnPrivileges($roleId));
    }

    public function getInheritedPrivilegesIds($roleId)
    {
        return array_diff($this->getRolesIdsBelowPrivilegesIds($roleId), $this->getRoleOwnPrivilegesIds($roleId));
    }

    // own privileges:

    public function getRoleOwnPrivileges($roleId)
    {
        return \App\Role::active()->find($roleId)->privileges()->pluck('name')->all();
    }

    public function getRoleOwnPrivilegesIds($roleId)
    {
        return \App\Role::active()->find($roleId)->privileges()->pluck('id')->all();
    }

    // own and inherited privileges:

    public function getRoleOwnAndInheritedPrivileges($roleId)
    {
        return array_merge($this->getRoleOwnPrivileges($roleId), $this->getRolesIdsBelowPrivileges($roleId));
    }

    public function getRoleOwnAndInheritedPrivilegesIds($roleId)
    {
        return array_merge($this->getRoleOwnPrivilegesIds($roleId), $this->getRolesIdsBelowPrivilegesIds($roleId));
    }

    // available privileges:

    public function getRoleAvailablePrivileges($roleId)
    {
        return array_diff($this->getAllPrivileges(), $this->getRolesIdsBelowPrivileges($roleId));
    }

    public function getRoleAvailablePrivilegesIds($roleId)
    {
        return array_diff($this->getAllPrivilegesIds(), $this->getRolesIdsBelowPrivilegesIds($roleId));
    }


    public function fetchAllPrivileges($keyName = false, $valueName = false)
    {
        return (!$keyName) ? \App\Privilege::ordered()->pluck('name', 'id')->all() : \App\Privilege::select("id AS $keyName", "privilege AS $valueName")->ordered()->get()->toArray();
    }

    /** protected PRIVILEGE functions: */

    protected function getPrivilegeIdFromName($privilegeName)
    {
        return \App\Privilege::where('name', $privilegeName)->value('id');
    }

}
