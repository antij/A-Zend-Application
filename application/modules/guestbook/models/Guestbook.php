<?php

/**
 * Guestbook model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single
 * guestbook entry.
 *
 * @uses       Guestbook_Model_GuestbookMapper
 * @package    QuickStart
 * @subpackage Model
 */
class Guestbook_Model_Guestbook
{
    /**
     * @var string
     */
    protected $_comment;

    /**
     * @var string
     */
    protected $_created;

    /**
     * @var string
     */
    protected $_email;

    /**
     * @var int
     */
    protected $_id;

    /**
     * @var Guestbook_Model_GuestbookMapper
     */
    protected $_mapper;

    /**
     * Constructor
     *
     * @param  array|null $options
     * @return void
     */
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Overloading: allow property access
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if ('mapper' == $name || !method_exists($this, $method)) {
            throw Exception('Invalid property specified');
        }
        $this->$method($value);
    }

    /**
     * Overloading: allow property access
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if ('mapper' == $name || !method_exists($this, $method)) {
            throw Exception('Invalid property specified');
        }
        return $this->$method();
    }

    /**
     * Set object state
     *
     * @param  array $options
     * @return Guestbook_Model_Guestbook
     */
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Set comment
     *
     * @param  string $text
     * @return Guestbook_Model_Guestbook
     */
    public function setComment($text)
    {
        $this->_comment = (string) $text;
        return $this;
    }

    /**
     * Get comment
     *
     * @return null|string
     */
    public function getComment()
    {
        return $this->_comment;
    }

    /**
     * Set email
     *
     * @param  string $email
     * @return Guestbook_Model_Guestbook
     */
    public function setEmail($email)
    {
        $this->_email = (string) $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return null|string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Set created timestamp
     *
     * @param  string $ts
     * @return Guestbook_Model_Guestbook
     */
    public function setCreated($ts)
    {
        $this->_created = $ts;
        return $this;
    }

    /**
     * Get entry timestamp
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->_created;
    }

    /**
     * Set entry id
     *
     * @param  int $id
     * @return Guestbook_Model_Guestbook
     */
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }

    /**
     * Retrieve entry id
     *
     * @return null|int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set data mapper
     *
     * @param  mixed $mapper
     * @return Guestbook_Model_Guestbook
     */
    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    /**
     * Get data mapper
     *
     * Lazy loads Guestbook_Model_GuestbookMapper instance if no mapper registered.
     *
     * @return Guestbook_Model_GuestbookMapper
     */
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Guestbook_Model_GuestbookMapper());
        }
        return $this->_mapper;
    }

    /**
     * Save the current entry
     *
     * @return void
     */
    public function save()
    {
        $this->getMapper()->save($this);
    }

    /**
     * Find an entry
     *
     * Resets entry state if matching id found.
     *
     * @param  int $id
     * @return Guestbook_Model_Guestbook
     */
    public function find($id)
    {
        $this->getMapper()->find($id, $this);
        return $this;
    }

    /**
     * Fetch all entries
     *
     * @return array
     */
    public function fetchAll()
    {
        return $this->getMapper()->fetchAll();
    }
}
