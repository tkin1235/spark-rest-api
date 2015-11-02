<?php
/**
 * Created by PhpStorm.
 * User: kisgal21
 * Date: 11/1/15
 * Time: 2:37 PM
 */


class tokensModel extends dbModel
{

    function __construct(\MongoCollection $tokenCollection)
    {
        parent::__construct($tokenCollection);
        $this->setCollection($tokenCollection);

    }

    private function getToken($token){

        $tokenResult = $this->collection->findOne([
            'token' => $token
        ]);

        if(empty($tokenResult)){
            throw new \Exception('token not found!');
        }else{
            return $tokenResult;
        }
    }

    public function getTokenAccess($token){
        if(!$access = $this->getToken($token)['access']){
            throw new \Exception('token access not found!');
        }
        return $access;
    }

    public function getUserFromToken($token){
        if(!$userID = $this->getToken($token)['user_id']){
            throw new \Exception('token user not found!');
        }
        return $userID;
    }

}
