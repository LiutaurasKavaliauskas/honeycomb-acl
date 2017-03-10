<?php namespace interactivesolutions\honeycombacl\app\http\controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use interactivesolutions\honeycombacl\app\validators\HCUsersValidator;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use interactivesolutions\honeycombacl\app\models\HCUsers;

class HCUsersController extends HCBaseController
{
    /**
     * Returning configured admin view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminView ()
    {
        $config = [
            'title'       => trans ('HCACL::users.page_title'),
            'listURL'     => route ('admin.api.users'),
            'newFormUrl'  => route ('admin.api.form-manager', ['users-new']),
            'editFormUrl' => route ('admin.api.form-manager', ['users-edit']),
            'imagesUrl'   => route ('resource.get', ['/']),
            'headers'     => $this->getAdminListHeader (),
        ];

        if ($this->user()->can ('interactivesolutions_honeycomb_acl_users_create'))
            $config['actions'][] = 'new';

        if ($this->user()->can ('interactivesolutions_honeycomb_acl_users_update')) {
            $config['actions'][] = 'update';
            $config['actions'][] = 'restore';
        }

        if ($this->user()->can ('interactivesolutions_honeycomb_acl_users_delete'))
            $config['actions'][] = 'delete';

        if ($this->user()->can ('interactivesolutions_honeycomb_acl_users_search'))
            $config['actions'][] = 'search';

        return view ('HCCoreUI::admin.content.list', ['config' => $config]);
    }

    /**
     * Creating Admin List Header based on Main Table
     *
     * @return array
     */
    public function getAdminListHeader ()
    {
        return [
            'activated_at'   => [
                "type"  => "text",
                "label" => trans ('HCACL::users.activated_at'),
            ],
            'last_login'     => [
                "type"  => "text",
                "label" => trans ('HCACL::users.last_login'),
            ],
            'last_visited'   => [
                "type"  => "text",
                "label" => trans ('HCACL::users.last_visited'),
            ],
            'last_activity'  => [
                "type"  => "text",
                "label" => trans ('HCACL::users.last_activity'),
            ],
        ];
    }

    /**
     * Create item
     *
     * @param array|null $data
     * @return mixed
     */
    protected function __create (array $data = null)
    {
        if (is_null ($data))
            $data = $this->getInputData ();

        (new HCUsersValidator())->validateForm ();

        $record = HCUsers::create (array_get ($data, 'record'));

        //TODO roleUser only
        $record->roleSuperAdmin();

        return $this->getSingleRecord ($record->id);
    }

    /**
     * Updates existing item based on ID
     *
     * @param $id
     * @return mixed
     */
    protected function __update (string $id)
    {
        $record = HCUsers::findOrFail ($id);

        //TODO read request parameters only once fo getting data and validating it
        $data = $this->getInputData ();
        (new HCUsersValidator())->validateForm ();

        $record->update (array_get ($data, 'record'));

        return $this->getSingleRecord ($record->id);
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed|void
     */
    protected function __delete (array $list)
    {
        HCUsers::destroy ($list);
    }

    /**
     * Delete records table
     *
     * @param $list
     * @return mixed|void
     */
    protected function __forceDelete (array $list)
    {
        HCUsers::onlyTrashed ()->whereIn ('id', $list)->forceDelete ();
    }

    /**
     * Restore multiple records
     *
     * @param $list
     * @return mixed|void
     */
    protected function __restore (array $list)
    {
        HCUsers::whereIn ('id', $list)->restore ();
    }

    /**
     * Creating data query
     *
     * @param array $select
     * @return mixed
     */
    public function createQuery(array $select = null)
    {
        $with = [];

        if ($select == null)
            $select = HCUsers::getFillableFields();

        $list = HCUsers::with($with)->select($select)
            // add filters
            ->where(function ($query) use ($select) {
                $query = $this->getRequestParameters($query, $select);
            });

        // enabling check for deleted
        $list = $this->checkForDeleted($list);

        // add search items
        $list = $this->listSearch($list);

        // ordering data
        $list = $this->orderData($list, $select);

        return $list;
    }

    /**
     * List search elements
     * @param $list
     * @return mixed
     */
    protected function listSearch (Builder $list)
    {
        if (request ()->has ('q')) {
            $parameter = request ()->input ('q');

            $list = $list->where (function ($query) use ($parameter) {
                $query->where ('activated_at', 'LIKE', '%' . $parameter . '%')
                    ->orWhere ('remember_token', 'LIKE', '%' . $parameter . '%')
                    ->orWhere ('last_login', 'LIKE', '%' . $parameter . '%')
                    ->orWhere ('last_visited', 'LIKE', '%' . $parameter . '%')
                    ->orWhere ('last_activity', 'LIKE', '%' . $parameter . '%');
            });
        }

        return $list;
    }

    /**
     * Getting user data on POST call
     *
     * @return mixed
     */
    protected function getInputData ()
    {
        $_data = request ()->all ();

        array_set ($data, 'record.email', array_get ($_data, 'email'));
        array_set ($data, 'record.password', Hash::make (array_get ($_data, 'password')));

        return $data;
    }

    /**
     * Getting single record
     *
     * @param $id
     * @return mixed
     */
    public function getSingleRecord (string $id)
    {
        $with = [];

        $select = HCUsers::getFillableFields ();

        $record = HCUsers::with ($with)
            ->select ($select)
            ->where ('id', $id)
            ->firstOrFail ();

        return $record;
    }
}
