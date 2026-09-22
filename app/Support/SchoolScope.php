<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

/**
 * Helper to scope queries by the current operator's school.
 *
 * Behavior:
 *  - SUPERUSER / ADMIN  -> can see ALL schools (no filter applied)
 *  - OPERATOR           -> only see records belonging to their own id_sekolah
 *
 * If the current user has no id_sekolah (NULL), OPERATOR will get an empty result
 * so they cannot accidentally see other schools' data.
 */
class SchoolScope
{
    /** Roles that are allowed to see across schools. */
    public static function globalViewers(): array
    {
        return ['SUPERUSER', 'ADMIN'];
    }

    /** True if current user can see records from any school. */
    public static function isGlobalViewer(): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return in_array($user->level, self::globalViewers(), true);
    }

    /**
     * Get the id_sekolah of the currently logged-in user,
     * or NULL if user can see everything (SUPERUSER/ADMIN).
     */
    public static function currentIdSekolah(): ?string
    {
        $user = Auth::user();
        if (!$user) return null;
        if (in_array($user->level, self::globalViewers(), true)) {
            return null;
        }
        return $user->id_sekolah ?: null;
    }

    /**
     * Apply id_sekolah filter to an Eloquent query or a DB query builder.
     * Returns the same builder (chainable).
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $q
     * @param string $column  column name to filter by (default 'id_sekolah')
     */
    public static function apply($q, string $column = 'id_sekolah')
    {
        $scope = self::currentIdSekolah();
        if ($scope !== null) {
            $q->where($column, $scope);
        }
        return $q;
    }

    /**
     * True if the current user is allowed to access a row belonging
     * to $rowSchoolId. ADMIN/SUPERUSER always true; OPERATOR only their own.
     * If user has no id_sekolah, returns false (deny-by-default).
     */
    public static function userCanAccessSchool($rowSchoolId): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        if (in_array($user->level, self::globalViewers(), true)) {
            return true;
        }
        return !empty($user->id_sekolah) && $user->id_sekolah === $rowSchoolId;
    }
}