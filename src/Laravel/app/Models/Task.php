<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * ステータス（状態）定義
     *
     */
    const STATUS = [
        1 => ['label' => '未着手', 'class' => 'label-danger'],
        2 => ['label' => '着手中', 'class' => 'label-info'],
        3 => ['label' => '完了', 'class' => ''],
    ];

    /**
     * ステータス（状態）ラベルのアクセサメソッド
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $status = $this->attributes['status'];

        if (!isset(self::STATUS[$status])) {
            return '';
        }
        return self::STATUS[$status]['label'];
    }

    public function getStatusClassAttribute()
    {
        // ステータス（状態）カラムの値を取得する
        $status = $this->attributes['status'];

        // STATUSに定義されていない場合
        if (!isset(self::STATUS[$status])) {
            // 空文字を返す
            return '';
        }
        // STATUSの値（['class']）を返す
        return self::STATUS[$status]['class'];
    }
}
