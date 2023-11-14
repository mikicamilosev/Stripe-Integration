<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net
 */

class ASDatabase extends PDO
{
    protected $debug = false;

    /**
     * Class constructor
     * Parameters defined as constants in ASConfig.php file
     * @param $type string Database type
     * @param $host string Database host
     * @param $databaseName string Database username
     * @param $username string User's username
     * @param $password string Users's password
     */
    public function __construct($type, $host, $databaseName, $username, $password)
    {
        parent::__construct($type.':host='.$host.';dbname='.$databaseName.';charset=utf8', $username, $password);
        $this->exec('SET CHARACTER SET utf8');
    }

    /**
     * Enable/disable debug for database queries.
     * @param $debug boolean TRUE to enable debug, FALSE otherwise.
     */
    public function debug(bool $debug): void
    {
        $this->debug = $debug;

        if ($debug) {
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } else {
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
    }

    /**
     * Get current debug status
     *
     * @return bool TRUE if debug mode is enabled, FALSE otherwise.
     */
    public function getDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Select the data from db
     */
    public function select(string $sql, array $bindings = [], int $fetchMode = PDO::FETCH_ASSOC): array
    {
        $sth = $this->prepare($sql);

        foreach ($bindings as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        $sth->execute();

        return $sth->fetchAll($fetchMode);
    }

    /**
     * Insert data to database.
     *
     * @param string $table A name of table to insert into
     * @param array $data string An associative array
     */
    public function insert(string $table, array $data): void
    {
        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        $sth->execute();
    }

    /**
     * Update
     *
     * @param $table string A name of table to insert into.
     * @param $data array An associative array where keys have the same name as database columns.
     * @param $where string the WHERE query part.
     * @param $whereBindArray array Parameters to bind to where part of query.
     */
    public function update(string $table, array $data, string $where, array $whereBindArray = []): void
    {
        ksort($data);

        $fieldDetails = null;

        foreach ($data as $key => $value) {
            $fieldDetails .= "`$key`=:$key,";
        }

        $fieldDetails = rtrim($fieldDetails, ',');

        $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        foreach ($whereBindArray as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        $sth->execute();
    }

    /**
     * Delete query.
     *
     * IF YOU USE PREPARED STATEMENTS, DON'T FORGET TO UPDATE $bind ARRAY!
     */
    public function delete(string $table, string $where, array $bind = [], int $limit = null)
    {
        $query = "DELETE FROM $table WHERE $where";

        if ($limit) {
            $query .= " LIMIT $limit";
        }

        $sth = $this->prepare($query);

        foreach ($bind as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        $sth->execute();
    }
}
