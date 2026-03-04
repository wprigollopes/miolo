<?php

use App\Database\MModel;

/**
 * Example model demonstrating MModel usage.
 *
 * Usage:
 *   $p = MExampleProduto::find(42);
 *   $p->nome = 'Widget';
 *   $p->save();
 *
 *   MExampleProduto::ativos()->where('preco', '>', 100)->get();
 *
 *   MEloquentTransaction::run(function () {
 *       MExampleProduto::create(['nome' => 'New', 'preco' => 9.99, 'ativo' => true]);
 *   });
 */
class MExampleProduto extends MModel
{
    const TABLENAME  = 'produto';
    const PRIMARYKEY = 'id';

    protected $connection = 'default';
    protected $fillable = ['nome', 'preco', 'ativo'];
    protected $casts = [
        'preco' => 'float',
        'ativo' => 'boolean',
    ];

    /** Relationship example: produto belongs to a categoria */
    public function categoria()
    {
        return $this->belongsTo(MExampleCategoria::class, 'categoria_id');
    }

    /** Scope example: filter only active products */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
}
