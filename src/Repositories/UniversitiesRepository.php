<?php

namespace Repositories;

class UniversitiesRepository implements RepositoryInterface
{
    private $connector;

    /**
     * StudentsRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param $connector
     */
    public function __construct($connector)
    {
        $this->connector = $connector;
    }

    public function findAll($limit = 1000, $offset = 0)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM universities LIMIT :limit OFFSET :offset');
        $statement->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
        $statement->execute();

        return $this->fetchUniversityData($statement);
    }

    private function fetchUniversityData($statement)
    {
        $results = [];
        while ($result = $statement->fetch()) {
            $results[] = [
                'id' => $result['id'],
                'universityName' => $result['university_name'],
                'city' => $result['city'],
                'siteUrl' => $result['site_url'],
            ];
        }
        return $results;
    }
    public function generate($count)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO universities (university_name, city, site_url) VALUES(:universityName, :city, :siteUrl)');
        $faker = \Faker\Factory::create();
        $insertedPKs = array();
        for ($i=0; $i < $count; $i++) {
            $statement->bindValue(':universityName', $faker->name, \PDO::PARAM_STR);
            $statement->bindValue(':city', $faker->city, \PDO::PARAM_STR);
            $statement->bindValue(':siteUrl', $faker->domainName, \PDO::PARAM_INT);
            $statement->execute();
            $insertedPKs[]= $this->connector->getPdo()->lastInsertId();
        }
    }

    public function insert(array $universityData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO universities (university_name, city, site_url) VALUES(:universityName, :city, :siteUrl)');
        $statement->bindValue(':universityName', $universityData['university_name']);
        $statement->bindValue(':city', $universityData['city']);
        $statement->bindValue(':siteUrl', $universityData['site_url']);
        
        return $statement->execute();
    }

    public function find($id)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM universities WHERE id = :id LIMIT 1');
        $statement->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $statement->execute();
        $studentsData = $this->fetchUniversityData($statement);

        return $studentsData[0];
    }

    public function update(array $universityData)
    {
        $statement = $this->connector->getPdo()->prepare("UPDATE universities SET university_name = :universityName, city = :city, site_url = :siteUrl WHERE id = :id");
        $statement->bindValue(':universityName', $universityData['university_name'], \PDO::PARAM_STR);
        $statement->bindValue(':city', $universityData['city'], \PDO::PARAM_STR);
        $statement->bindValue(':siteUrl', $universityData['site_url'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $universityData['id'], \PDO::PARAM_INT);
        
        return $statement->execute();
    }

    public function remove(array $universityData)
    {
        $statement = $this->connector->getPdo()->prepare("DELETE FROM universities WHERE id = :id");
        $statement->bindValue(':id', $universityData['id'], \PDO::PARAM_INT);
        return $statement->execute();
    }

    /**
     * Search all entity data in the DB like $criteria rules
     * @param array $criteria
     * @return mixed
     */
    public function findBy($criteria = [])
    {
        // TODO: Implement findBy() method.
    }
}