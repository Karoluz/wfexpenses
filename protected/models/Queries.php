<?php


class Queries
{


    public static function getNetWorth(){
        $sql = "SELECT (income - expense) as networth FROM (
            SELECT (SElect sum(amount) From transactions WHERE type = \"income\") as income,
            (SELECT sum(amount) FROM transactions WHERE type =\"expense\") as expense 
            FROM transactions group by income) t;";
        try{
            $results = Yii::app()->db->createCommand($sql)->queryScalar();
        }catch (Exception $e){
            return 0;
        }
        return $results;
    }

    public static function getIncome(){
        $sql = "SELECT sum(amount) FROM transactions WHERE type=\"income\";";
        try{
            $results = Yii::app()->db->createCommand($sql)->queryScalar();
        }catch (Exception $e){
            return 0;
        }
        return $results;
    }

    public static function getExpenses(){
        $sql = "SELECT sum(amount) FROM transactions WHERE type=\"expense\";";
        try{
            $results = Yii::app()->db->createCommand($sql)->queryScalar();
        }catch (Exception $e){
            return 0;
        }
        return $results;
    }

    public static function getSavings(){
        $sql = "SELECT (income - expense) as networth FROM (
            SELECT (SElect sum(amount) From transactions WHERE type = \"income\") as income,
            (SELECT sum(amount) FROM transactions WHERE type =\"expense\") as expense 
            FROM transactions group by income) t;";
        try{
            $results = Yii::app()->db->createCommand($sql)->queryScalar();
        }catch (Exception $e){
            return 0;
        }
        return $results;
    }

    public static function getIncomeByMonth(){
        $sql = "SELECT sum(amount) as total,Month(trans_date) as date 
        FROM transactions WHERE type=\"income\" group by MONTH(trans_date);";
        try{
            $results = Yii::app()->db->createCommand($sql)->queryAll();
        }catch (Exception $e){
            return null;
        }
        return $results;
    }
}
