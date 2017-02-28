<?php

namespace interactivesolutions\honeycombacl\app\models\acl;

use interactivesolutions\honeycombcore\models\HCModel;

class RolesPermissionsConnections extends HCModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_acl_roles_permissions_connections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','role_id','permission_id'];

}
