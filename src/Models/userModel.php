<?php
/**
 * Created by PhpStorm.
 * User: kisgal21
 * Date: 11/1/15
 * Time: 2:37 PM
 */

class userModel extends dbModel
{
    protected $user = [
        'id' => 0,
        'name' => '',
        'role' => '',
        'phone' => '',
        'created_at' => null,
        'updated_at' => null,
    ];

    public $userIdField = 'id';
    public $userNameField = 'name';
    public $userRoleField = 'role';
    public $userPhoneField = 'phone';
    public $userCreatedAtField = 'created_at';
    public $userUpdatedAtField = 'updated_at';

    /**
     * @param \MongoCollection $userCollection
     * @throws userModelException
     */
    function __construct(\MongoCollection $userCollection)
    {
        if (!$userCollection instanceof \MongoCollection) {
            throw new userModelException('mongo collection is required!');
        }

        parent::__construct($userCollection);
        $this->setCollection($userCollection);
    }

    /**
     * @param $id
     * @return array|null
     * @throws userModelException
     */
    public function getUserById($id)
    {
        if (!isset($id)) {   //TODO type id
            throw new userModelException('id is required');
        }

        return $this->collection->findOne([
            'id' => $id
        ]);
    }

    /**
     * @param $query
     * @return array
     * @throws userModelException
     */
    public function findUserByArray($query)
    {
        foreach ($query as $index => $value) {
            if (!isset($this->user[$index])) {
                throw new userModelException('ERROR: the field: ' . $index . ' doesn\'t exist on the user object');
            }
        }

        $result = $this->collection->find($query);
        return iterator_to_array($result);
    }

    public function getUsersByIds($ids){

        $query = ['$or' => []];
        foreach($ids as $id){
            $query['$or'][] = ['_id' => new MongoId($id)];
        }
        $users = $this->collection->find($query);

        return iterator_to_array($users);
    }

    /**
     * @param $label
     * @param $value
     * @return array
     * @throws userModelException
     */
    public function getUsersByField($label, $value)
    {
        return iterator_to_array($this->getUserByField($label, $value));
    }

    /**
     * @param $label
     * @param $value
     * @return \MongoCursor
     * @throws userModelException
     */
    private function getUserByField($label, $value)
    {
        if (!$label) {
            throw new userModelException('id is required');
        }
        if (!$value) {
            throw new userModelException('id is required');
        }

        return $this->collection->find([
            $label => $value
        ]);
    }

    private function userQuery($query = [])
    {
        return $this->collection->find($query);
    }
}


class userModelException Extends \Exception
{

}

