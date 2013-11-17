<?php

/*
 * DataTable service
 *
 * h1. Installation
 *      Add in services.yml
 *
 *          hotflo.datatable:
 *          class: Hotflo\System\CoreBundle\Helper\Datatable
 *          arguments:
 *              em: @doctrine
 *
 * h2. Usage example
 *      $dataTable = $this->get('hotflo.datatable');
 *      $dataTable->setEntity('HotfloSystemCoreBundle:OrYearSchedule', 'orYearSchedule');
 *      $dataTable->setRequest($request);
 *      $dataTable->setSelectParameters(array(
 *          'id',
 *          'name',
 *          'status',
 *          'year',
 *          array(
 *              'orSession',                    // Entity
 *              array('id as orSessionId'),     // Entity properties
 *              'innerJoin'                     // JoinType (innerJoin or leftJoin)
 *              null,                           // Optional: ConditionType
 *              null                            // Optional: Condition
 *          )
 *      ));
 *      $dataTable->makeSearch();
 *      return $dataTable->sendResponse();
 *
 */

namespace Piwicms\System\CoreBundle\Helper;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Datatable
{
    /**
     * Doctrine innerJoin type
     */
    const JOIN_INNER = 'inner';

    /**
     * Doctrine leftJoin type
     */
    const JOIN_LEFT = 'left';

    /**
     * Doctrine object
     *
     * @var Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    /**
     * The QueryBuilder
     *
     * @var Doctrine\ORM\QueryBuilder
     */
    protected $qb;

    /**
     * Query results
     *
     * @var array
     */
    protected $results;

    /**
     * Request object
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Used Entity
     *
     * @var string
     */
    protected $entity;

    /**
     * Entity short name
     *
     * @var string
     */
    protected $entityShortName;

    /**
     * Select paramaters in QueryBuilder
     *
     * @var array
     */
    protected $selectParameters;

    /**
     * Allow to add extra where statements
     * This property is used as a callback
     *
     * @var function
     */
    protected $advancedWhere;

    /**
     * Allow to add extra join statements
     * This property is used as a callback
     *
     * @var function
     */
    protected $advancedJoin;

    /**
     * Information for the DataTables jQuery plugin to use for rendering
     *
     * @var integer
     */
    protected $sEcho;

    /**
     * Number of columns being displayed
     *
     * @var integer
     */
    protected $iColumns;

    /**
     * A string of column names, comma seperated, which allow DataTables to reorder data on the
     * client-side if required for display. The explode function creates an array from this string
     *
     * @var array
     */
    protected $sColumns;

    /**
     * Display start point in the current data set
     *
     * @var integer
     */
    protected $iDisplayStart;

    /**
     * Number of records that the table can display in the current draw
     *
     * @var integer
     */
    protected $iDisplayLength;

    /**
     * Global search field
     *
     * @var string
     */
    protected $sSearch;

    /**
     * True if the global filter should be treated as a regulsar expression for advanced filtering,
     * false if not. NOTE: mySQL can't handle regular expressions in WHERE clause!
     * Not implemented, but mandatory for DataTables: always false!
     *
     * @var boolean
     */
    protected $bRegex;

    /**
     * Number of columns to sort on
     *
     * @var integer
     */
    protected $iSortingCols;

    /**
     * mDataProp, sSearch, bRegex, bSearchable and bSortable column properties
     * Example:
     * $mColumns[0] = array(
     *      "sColumn" => "task",            Column name which corresponds with the column name from the entity
     *      "mDataProp" => "function",      The value specified by mDataProp for each column
     *      "sSearch" => "",                Individual column filter
     *      "bRegex" => "true",             True if the individual column filter should be treated as a
     *                                      regular expression for advanced filter, false if not.
     *                                      NOTE: mySQL can't handle regular expressions in WHERE clause!
     *                                      Not implemented, but mandatory for DataTables: always false!
     *      "bSearchable" => "true",        Indicator for if a column is flagged as searchable or not
     *      "bSortable" => "true",          Indicator for if a column is flagged as sortable or not
     *      "sSortDir" => ""                Direction to be sorted (desc of asc)
     * );
     *
     * @var array
     */
    protected $mColumns;

    /**
     * DataTable constructor
     *
     * @param Doctrine $doctrine
     * @param AjaxResponse $ajaxResponse
     */
    public function __construct(Doctrine $doctrine)
    {
        // The whole Doctrine service
        $this->doctrine = $doctrine;
    }

    /**
     * Set request property
     *
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Set entity property
     * Possible inputs: namespace or magic string
     * Example:
     *      - Hotflo\System\CoreBundle\entity\Task
     *      - HotfloSystemCoreBundle:Task
     *
     * @param $entity
     */
    public function setEntity($entity, $shortName = null)
    {
        $this->entity = $entity;
        $this->entityShortName = $shortName;
    }

    /**
     * Set select parameters
     *
     * @param array $selectParameters
     *
     */
    public function setSelectParameters(array $selectParameters)
    {
        $parameters = array();
        foreach ($selectParameters as $parameter) {
            if (!is_array($parameter)) {
                if (!strstr($parameter, '.')) {
                    $parameter = $this->entityShortName . "." . $parameter;
                }
            }
            $parameters[] = $parameter;
        }
        $this->selectParameters = $parameters;
    }

    /**
     * Set advanced where clause
     *
     * @param $advancedWhere
     */
    public function setAdvancedWhere($advancedWhere)
    {
        $this->advancedWhere = $advancedWhere;
    }

    /**
     * Set advanced where clause
     *
     * @param $advancedWhere
     */
    public function setAdvancedJoin($advancedJoin)
    {
        $this->advancedJoin = $advancedJoin;
    }

    /**
     * Make search
     */
    public function makeSearch()
    {
        $this->setDatatableMandatoryParameters();
        $this->createQueryBuilder();
        $this->createSelect();
        $this->createWhere($this->qb);
        $this->createJoins();
        $this->createOrderBy();
        $this->createLimit();
//echo $this->qb->getQuery()->getSQL();
        // getQuery and save results in class property
        $this->results = $this->qb->getQuery()->getResult();
    }

    /**
     * Get results
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set results
     */
    public function setResults($results)
    {
        return $this->results = $results;
    }

    /**
     * @return int
     */
    public function getSEcho()
    {
        return $this->sEcho;
    }


    /**
     * Send response
     *
     * @return JsonResponse
     */
    public function sendResponse()
    {
        $data = array(
            "aaData" => $this->results,
            "sEcho" => $this->sEcho,
            "iTotalRecords" => $this->getCountAllResults(),
            "iTotalDisplayRecords" => $this->getCountFilteredResults()
        );
//        return $this->results;
        $response = new JsonResponse();
        $response->setData($data);
        return $response;
    }

    /**
     * Create the QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $entityManager = $this->doctrine->getManager();
        $repository = $entityManager->getRepository($this->entity);
        // Create QueryBuilder instance
        $this->qb = $repository->createQueryBuilder($this->entityShortName);
    }

    protected function createJoins($qb = null)
    {
        if ($qb == null) {
            $qb = $this->qb;
        }
        if (is_callable($this->advancedJoin)) {
            $advancedJoin = $this->advancedJoin;
            $advancedJoin($qb);
        }
        $joins = array();
        // Loop trough the select parameters
        foreach ($this->selectParameters as $parameter) {
            // Check if parameter is an array
            if (is_array($parameter)) {
                // If, It contains minimal 3 parameters and max 5 parameters
                // Put that in the $joins array which is handled later this function
                $joins[] = array (
                    "propertyName"  => $parameter[0],
                    "joinType"      => (!isset($parameter[2]) ? 'innerJoin' : $parameter[2]),
                    "conditionType" => (!isset($parameter[3]) ? null : $parameter[3]),
                    "condition"     => (!isset($parameter[4]) ? null : $parameter[4]),
                    "joinProperty"  => (!isset($parameter[5]) ? null : $parameter[5]),
                    "shortName"  => (!isset($parameter[6]) ? null : $parameter[6]),
                );
            }
        }
        // Check if the $joins array isn't empty
        if (!empty($joins)) {
            // Loop through the joins
            foreach ($joins as $join) {
                // Set variables
                $propertyName   = $join['propertyName'];
                $joinType       = $join['joinType'];
                $conditionType  = $join['conditionType'];
                $condition      = $join['condition'];
                $joinProperty   = $join['joinProperty'];
                $shortName      = $join['shortName'];
                if ($joinProperty == null) {
                    $joinProperty = $this->entityShortName;
                }
                if ($shortName == null) {
                    $shortName = $propertyName;
                }
                // Choose the right joinType
                switch ($joinType) {
                    case 'innerJoin':
                        $qb->innerJoin(
                            $joinProperty . "." . $propertyName,
                            $shortName,
                            $conditionType,
                            $condition
                        );
                        break;
                    case 'leftJoin':
                        $qb->leftJoin(
                            $joinProperty . "." . $propertyName,
                            $shortName,
                            $conditionType,
                            $condition
                        );
                        break;
                }
            }
            $this->qb = $qb;
        }
        return $qb;
    }

    /**
     * Create select query
     */
    protected function createSelect()
    {
        $select = array();
        // Loop trough the select parameters
        foreach ($this->selectParameters as $parameter) {
            // Check if parameter is an array
            if (is_array($parameter)) {
                // Set variables
                $propertyName       = (!empty($parameter[0]) ? $parameter[0] : null);
                $selectName         = (!empty($parameter[1]) ? $parameter[1] : null);
                $joinType           = (!empty($parameter[2]) ? $parameter[2] : null);
                $conditionType      = (!empty($parameter[3]) ? $parameter[3] : null);
                $condition          = (!empty($parameter[4]) ? $parameter[4] : null);
                $joinProperty       = (!empty($parameter[5]) ? $parameter[5] : null);
                $shortName          = (!empty($parameter[6]) ? $parameter[6] : null);

                if (!isset($shortName)) {
                    $shortName = $propertyName;
                }

                // If the second parameter is an array loop through it
                if (is_array($selectName)) {
                    foreach ($selectName as $property) {
                        // Add properties to the select query
                        if (!strstr($property, '.')) {
                            $select[] = $shortName . "." . $property;
                        } else {
                            $select[] = $property;
                        }
                    }
                } else {
                    // Add properties to the select query
                    if (!strstr($selectName, '.')) {
                        $select[] = $shortName . "." . $selectName;
                    } else {
                        $select[] = $selectName;
                    }
                }
            } else {
                // Add properties to the select query
                $select[] = $parameter;
            }
        }
        // Put the $select array in the QueryBuilder
        $this->qb->select($select);
    }

    /**
     * Create Where clause
     *
     * @param $qb
     */
    protected function createWhere($qb)
    {
        $this->setFilters($qb);
        if (is_callable($this->advancedWhere)) {
            $advancedWhere = $this->advancedWhere;
            $advancedWhere($qb);
        }
    }

    /**
     * Set filters
     *
     * @param $qb
     */
    protected function setFilters($qb)
    {
        // Global filtering
        $orExpr = $qb->expr()->orX();
        if (isset($this->sSearch) && !empty($this->sSearch)) {
            foreach ($this->mColumns as $i => $column) {
                if ($column['bSearchable'] == null || $column['bSearchable'] == "true") {
                    foreach(explode('+', $column['sColumn']) as $_column) {
                        $orExpr->add($qb->expr()->like(
                            $_column,
                            ':sSearch_global_' . $i
                        ));
                        $qb->setParameter('sSearch_global_' . $i, "%" . $this->sSearch . "%");
                    }
                }
            }
            $qb->where($orExpr);
        }

        // Individual column filtering
        $andExpr = $qb->expr()->andX();
        $i = 0;
        foreach ($this->mColumns as $i => $column) {
            if (isset($column['bSearchable']) && $column['bSearchable'] == "true" && !empty($column['sSearch'])) {
                foreach(explode('+', $column['sColumn']) as $_column) {
                    $andExpr->add($qb->expr()->like(
                        $_column,
                        ':sSearch_single_' . $i
                    ));
                    $qb->setParameter(':sSearch_single_' . $i, "%" . $column['sSearch'] . "%");
                    $i++;
                }
            }
        }
        if ($andExpr->count() > 0) {
            $qb->andWhere($andExpr);
        }
    }

    /**
     * Set Order By
     */
    protected function createOrderBy()
    {
        foreach ($this->mColumns as $column) {
            if (!empty($column['sSortDir'])) {
                $this->qb->addOrderBy(
                    $column['sColumn'],
                    $column['sSortDir']
                );
            }
        }
    }

    /**
     * Set Query limit
     */
    protected function createLimit()
    {
        if (isset($this->iDisplayStart) && $this->iDisplayLength != '-1') {
            $this->qb->setFirstResult($this->iDisplayStart)->setMaxResults($this->iDisplayLength);
        }
    }

    /**
     * Set the mandatory parameters for the DataTable jQuery plugin
     * Mandatory parameters are:
     * - sEcho                      integer
     * - iColumns                   integer
     * - sColumns                   string
     * - iDisplayStart              integer
     * - iDisplayLength             integer
     * - sSearch                    string
     * - bRegex                     boolean
     * - mDataProp_{int}            string
     * - sSearch_{int}              string
     * - bRegex_{int}               boolean
     * - bSearchable_{int}          boolean
     * - bSortable_{int}            boolean
     * - iSortingCols               integer
     * - iSortCol_{int}             integer
     * - bSortDir_{int}             string (asc of desc, lowercase)
     * Detailed information available in the class properties or at the DataTables website
     * Visit http://datatables.net/usage/server-side
     */
    protected function setDatatableMandatoryParameters()
    {
        $parameters = $this->request->query->all();
        $this->sEcho = (!empty($parameters['sEcho']) ? $parameters['sEcho'] : '');
        $this->iColumns = (!empty($parameters['iColumns']) ? $parameters['iColumns'] : '');
        $this->sColumns = (!empty($parameters['sColumns']) ? explode(',', $parameters['sColumns']) : '');
        $this->iDisplayStart = (!empty($parameters['iDisplayStart']) ? $parameters['iDisplayStart'] : '');
        $this->iDisplayLength = (!empty($parameters['iDisplayLength']) ? $parameters['iDisplayLength'] : '');

        if (array_key_exists('sSearch', $parameters)){
            $this->sSearch = $parameters['sSearch'];
        }
        if (array_key_exists('bRegex', $parameters)){
            $this->bRegex = $parameters['bRegex'];
        }

        $this->iSortingCols = (!empty($parameters['iSortingCols']) ? $parameters['iSortingCols'] : '');
        // Loop trough all column settings: mDataProp, sSearch, bRegex, bSearchable and bSortable
        // Save this in an array as key => value
        // Example:
        // $mColumns[0] = array(
        //      "sColumn" => "task",            Column name which corresponds with the column name from the entity
        //      "mDataProp" => "function",      The value specified by mDataProp for each column
        //      "sSearch" => "",                Individual column filter
        //      "bRegex" => "true",             True if the individual column filter should be treated as a
        //                                      regular expression for advanced filter, false if not.
        //                                      NOTE: mySQL can't handle regular expressions in WHERE clause!
        //                                      Not implemented, but mandatory for DataTables: always false!
        //      "bSearchable" => "true",        Indicator for if a column is flagged as searchable or not
        //      "bSortable" => "true",          Indicator for if a column is flagged as sortable or not
        //      "sSortDir" => ""                Direction to be sorted (desc of asc)
        // );
        $mColumns = array();
        for ($i = 0; $i < $this->iColumns; $i++) {
            if (!empty($this->sColumns[$i])) {
                $sColumn = $this->sColumns[$i];
                if (!strstr($sColumn, '.')) {
                    $sColumn = $this->entityShortName . "." . $sColumn;
                }
                $mColumns[$i]['sColumn']        = $sColumn;
                $mColumns[$i]['mDataProp']      = $parameters['mDataProp_' . $i];
                if(array_key_exists('sSearch_', $parameters)){
                    $sSearch = $parameters['sSearch_' . $i];
                } else {
                    $sSearch = null;
                }
                $mColumns[$i]['sSearch']        = $sSearch;
                if(array_key_exists('bRegex_', $parameters)){
                    $bRegex = $parameters['bRegex_' . $i];
                } else {
                    $bRegex = null;
                }
                $mColumns[$i]['bRegex']         = $bRegex;
                $mColumns[$i]['sSearch']        = $sSearch;
                if(array_key_exists('bSearchable_', $parameters)){
                    $bSearchable = $parameters['bSearchable_' . $i];
                } else {
                    $bSearchable = null;
                }
                $mColumns[$i]['bSearchable']    = $bSearchable;
                if(array_key_exists('bSortable_', $parameters)){
                    $bSortable = $parameters['bSortable_' . $i];
                } else {
                    $bSortable = null;
                }
                $mColumns[$i]['bSortable']      = $bSortable;
            }
        }
        // Loop trough sorted columns
        if ($this->iSortingCols > 0) {
            for ($i = 0; $i < $this->iSortingCols; $i++) {
                if (isset($mColumns[$parameters['iSortCol_' . $i]])) {
                    $mColumns[$parameters['iSortCol_' . $i]]['sSortDir'] = $parameters['sSortDir_' . $i];
                }
            }
        }
        $this->mColumns = $mColumns;
    }

    /**
     * Count table
     *
     * @return int
     */
    public function getCountAllResults()
    {
        $repository = $this->doctrine->getRepository($this->entity);
        $qb = $repository->createQueryBuilder($this->entityShortName);
        $qb = $this->createJoins($qb);
        $qb->select('count(' . $this->entityShortName . '.id)');
        if (is_callable($this->advancedWhere)) {
            $advancedWhere = $this->advancedWhere;
            $advancedWhere($qb);
        }
        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Count results
     *
     * @return int
     */
    public function getCountFilteredResults()
    {
        $repository = $this->doctrine->getRepository($this->entity);
        $qb = $repository->createQueryBuilder($this->entityShortName);
        $qb = $this->createJoins($qb);
        $qb->select('count(' . $this->entityShortName . '.id)');
        $this->createWhere($qb);
        return (int) $qb->getQuery()->getSingleScalarResult();
    }

}