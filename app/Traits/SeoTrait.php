<?php

trait SeoTrait
{

    public function fetchSeoInfo($routeName)
    {
        $result = null;

        if (!empty($routeName)) {
            $row = \DB::table('seo')->where('route_name', $routeName)->first();

            if ($row) {
                $result = [
                    'pageTitle'   => $row->page_title,
                    'description' => $row->page_description,
                    'keywords'    => $row->page_keywords,
                ];
            }
        }

        return $result;
    }

}
