<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

/**
 * Base model class for MIOLO applications.
 *
 * Extends Eloquent's Model with MIOLO conventions. All new domain models
 * should extend this class instead of the legacy PersistentObject / MBusiness.
 *
 * Supports Adianti-style TABLENAME/PRIMARYKEY constants for backward compatibility
 * with existing naming conventions.
 *
 * Inherits from Eloquent for free:
 *   - Active Record: find(), save(), delete(), create(), update()
 *   - Query Builder: where(), orderBy(), limit(), count()
 *   - Relationships: hasMany(), belongsTo(), belongsToMany()
 *   - Attribute casting: protected $casts = ['price' => 'float']
 *   - Soft deletes: use SoftDeletes trait
 *   - Model events: creating, updating, deleting observers
 *   - Serialization: toArray(), toJson()
 *   - Scopes: scopeActive($query) for reusable filters
 */
abstract class MModel extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * Override in subclass to disable: public $timestamps = false;
     */
    public $timestamps = true;

    /**
     * Guard only the primary key by default.
     * Subclasses should define $fillable for mass-assignment protection.
     */
    protected $guarded = ['id'];

    /**
     * Support Adianti-style TABLENAME constant.
     *
     * Usage: const TABLENAME = 'produto';
     */
    #[\Override]
    public function getTable(): string
    {
        if (defined(static::class . '::TABLENAME')) {
            return static::TABLENAME;
        }
        return parent::getTable();
    }

    /**
     * Support Adianti-style PRIMARYKEY constant.
     *
     * Usage: const PRIMARYKEY = 'produto_id';
     */
    #[\Override]
    public function getKeyName(): string
    {
        if (defined(static::class . '::PRIMARYKEY')) {
            return static::PRIMARYKEY;
        }
        return parent::getKeyName();
    }
}

class_alias(MModel::class, 'MModel');
