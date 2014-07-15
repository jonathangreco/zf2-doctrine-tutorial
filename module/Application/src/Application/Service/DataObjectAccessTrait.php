<?php
/**
 * @package Application
 * @author Jonathan Greco <jgreco@docsourcing.com>
 * @author Bastien Hérault <bherault@docsourcing.com> 
 * Ce trait sert à factoriser le code de traitement de requêtes PDO
 */
namespace Application\Service;

use Doctrine\Common\Persistence\ObjectManager;

trait DataObjectAccessTrait
{
    /**
     * Execute une requête en PDO
     * Dépend de l'object manager de Doctrine pour récupérer la connexion : $conn
     * Retourne true ou un objet de type PDOstatement ou false en cas de problème
     * 
     * @param ObjectManager $conn
     * @param string $sql
     * @param array $params
     * @param boolean $log
     * @param boolean $returnStatement
     * @return mixed
     */
    public function execQuery(ObjectManager $conn, $sql, $params = array(), $log = true, $returnStatement = false)
    {
        $getConn = $conn->getConnection();
        $sth = $getConn->prepare($sql);
        if (!$sth->execute($params)) 
        {
            $this->log("Requête en échec -> {$sql}, paramètres ".print_r($params, true));
            return false;
        }
        if ($returnStatement) 
            return $sth;
        return true;
    }

    /**
     * Execute une requête en PDO et retourne les résultats
     * sous forme de tableau multiligne indexé (vide si aucun résultat)
     * Retourne false si une erreur survient
     * 
     * @param ObjectManager $conn
     * @param string $sql
     * @param array $params
     * @param boolean $log
     * @return mixed
     */
    public function fetchAll(ObjectManager $conn, $sql, $params = array(), $log = true)
    {
        if (!$sth = $this->execQuery($conn, $sql, $params, $log, true)) 
            return false;
        if ($sth->rowCount()<1) 
            return array();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Execute une requête en PDO et retourne une seule ligne de résultat
     * sous forme de tableau clé => valeur (vide si aucun résultat)
     * Retourne false si une erreur survient
     * 
     * @param ObjectManager $conn
     * @param string $sql
     * @param array $params
     * @param boolean $log
     * @return mixed
     */
    public function fetchRow(ObjectManager $conn, $sql, $params = array(), $log = true)
    {
        if (stripos($sql, ' LIMIT ')===false) 
            $sql .= ' LIMIT 1';
        if (!$sth = $this->execQuery($conn, $sql, $params, $log, true)) 
            return false;
        if ($sth->rowCount()<1) 
            return array();
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Execute une requête en PDO et retourne une valeur
     * sous forme de chaine (vide si aucun résultat)
     * Retourne false si une erreur survient
     * 
     * @param ObjectManager $conn
     * @param string $sql
     * @param array $params
     * @param boolean $log
     * @return mixed
     */
    public function fetchColumn(ObjectManager $conn, $sql, $params = array(), $log = true)
    {
        if (!$sth = $this->execQuery($conn, $sql, $params, $log, true)) 
            return false;
        if ($sth->rowCount()<1) 
            return '';
        return $sth->fetch(\PDO::FETCH_COLUMN);
    }
}