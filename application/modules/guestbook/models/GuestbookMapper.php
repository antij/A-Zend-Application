<?php

/**
 * Guestbook data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @uses       Guestbook_Model_DbTable_Guestbook
 * @package    QuickStart
 * @subpackage Model
 */
class Guestbook_Model_GuestbookMapper
{
    /**
     * @var Zend_Db_Table_Abstract
     */
    protected $_dbTable;

    /**
     * Specify Zend_Db_Table instance to use for data operations
     *
     * @param  Zend_Db_Table_Abstract $dbTable
     * @return Guestbook_Model_GuestbookMapper
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * Get registered Zend_Db_Table instance
     *
     * Lazy loads Guestbook_Model_DbTable_Guestbook if no instance registered
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Guestbook_Model_DbTable_Guestbook');
        }
        return $this->_dbTable;
    }

    /**
     * Save a guestbook entry
     *
     * @param  Guestbook_Model_Guestbook $guestbook
     * @return void
     */
    public function save(Guestbook_Model_Guestbook $guestbook)
    {
        $data = array(
            'email'   => $guestbook->getEmail(),
            'comment' => $guestbook->getComment(),
            'created' => date('Y-m-d H:i:s'),
        );

        if (null === ($id = $guestbook->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    /**
     * Find a guestbook entry by id
     *
     * @param  int $id
     * @param  Guestbook_Model_Guestbook $guestbook
     * @return void
     */
    public function find($id, Guestbook_Model_Guestbook $guestbook)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $guestbook->setId($row->id)
                  ->setEmail($row->email)
                  ->setComment($row->comment)
                  ->setCreated($row->created);
    }

    /**
     * Fetch all guestbook entries
     *
     * @return array
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Guestbook_Model_Guestbook();
            $entry->setId($row->id)
                  ->setEmail($row->email)
                  ->setComment($row->comment)
                  ->setCreated($row->created)
                  ->setMapper($this);
            $entries[] = $entry;
        }
        return $entries;
    }
}
