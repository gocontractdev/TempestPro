<?php


namespace App\Helpers;

use App\Permission;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class PermissionHelper
{
    public $user;

    /**
     * PermissionHelper constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function resolvePermissions() : Collection
    {
        $interactionList = new Collection();
        $role = $this->user->role;
        $interactions = $role->interactions;
        foreach ($interactions as $interaction) {
            $interactionList->add([
                'permission_id' => $interaction->permission_id,
                'target_role_id' => $interaction->target_role_id,
            ]);
        }
        return  $interactionList;
    }

    public function can(User $target, Permission $permission) : bool
    {
        if ($target->isSame($this->user)) {
            return true;
        }

        $sourceRole = $this->user->role;
        /** @var Collection $interactions */
        $interactions = $sourceRole->interactions;
        $match = $interactions->first(function($interaction) use ($target, $permission) {
            return ($interaction->permission_id == $permission->id &&
                $interaction->target_role_id == $target->role->id
            );

        });

        return (! empty($match));
    }
}
