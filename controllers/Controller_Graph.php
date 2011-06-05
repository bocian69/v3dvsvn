<?php

Class Controller_Graph Extends Controller_Base
{
	/**
	 *
	 * Starter
	 */
	public function index()
    {
        if (isset($_POST['action']))
        {
			if( method_exists('Controller_Graph', $_POST['action']) === true)
            {
				call_user_func(array(&$this, $_POST['action']));
				die;
		    }
		}
		echo 'Błąd! Żądana metoda nie istnieje.';
		die;
	}

	/**
	 *
	 * Ustawia argumenty przekazane z GET sformatowane przez router w postaci klucz=>wartosc
	 * @param $args
	 */

	public function setArgs($args) {
		$this->args = $args;
	}

    public $parsedQuery = array();
    public $sql_query = "";
    public $sql_query_def = '';
    private $regexps = array(
                                'select' => '/select.+?from/ims',
                                'from' => '/from(.+?where|.+)/ims',
                                'where' => '/where.+/ims'
                            );
    private $dispatchedSelect = '';
    private $dispatchedFrom = '';
    private $dispatchedWhere = '';

    private $sectionSelect = array();
    private $sectionFrom = array();
    private $sectionWhere = array();

    private $tablesUsed = array();
    private $aliases = array();
    public $circlesStructure = array();
    public $circlesStructures = array();
    public $levelsPortions = array();
    private $mainTable = '';

    /**
     * Pobieranie koordynatow w ukladzie kartezjanskim potrzebnych do narysowania lukow
     */
    public function getCoords()
    {
        if (false !== $this->noConstructorNoFun())
        {
            $this->sqlToArray();
            $this->countCords();
            $return_clean = $this->coordsCounted;
            $return = json_encode($return_clean);
        }
        else
        {
            $return = "Error Notifier";
        }
        
        print_r($return);
    }

    /**
     *  Pobranie zdispachowanego zapytania
     */
    public function getJoinsAndAliases()
    {
        if (false !== $this->noConstructorNoFun())
        {
            $this->sqlToArray();
            if (!empty($this->parsedQuery['join']))
            {
                foreach($this->parsedQuery['join'] as $v)
                {
                    foreach($v['on'] as $k2 => $v2)
                    {
                        foreach($v2 as $k3 => $v3)
                        {
                            if ( is_int($k3) and $v['to']['name'] != $v3['from'])
                            {
                                $from = $v3['from'];
                            }
                        }
                        $joins[] = array(
                            'from' => $from,
                            'to' => $v['to'],
                            'on' => array($v2),
                            'type' => $v['type']
                        );
                    }
                }

                $return_clean['joins'] = $joins;
            }
            
            $return_clean['aliases'] = $this->aliases;
            
            $return = json_encode($return_clean);
        }
        else
        {
            $return = "Error Notifier";
        }

        print_r($return);
    }

    private $idAndAlias = false;
    /**
     * Zwraca zapytanie zlozone z tablic
     */
    public function getQuery()
    {
        if (false !== $this->noConstructorNoFun())
        {
            $this->sqlToArray();
            
            $this->parsedQuery['join'] = array();
            
            if ( !empty($_POST['joins']))
            {
                foreach ($_POST['joins'] as $k => $v)
                {
                    if ('undefined' != $v)
                    {
                        $this->parsedQuery['join'][$k] = $v;
                    }
                    else
                    {
                        $this->idAndAlias = $_POST['idAndAlias'];
                    }
                }
            }
            $this->patchQueryArray();
           
            $return = $this->reparsedSelect . $this->reparsedFrom . $this->reparsedJoin . $this->reparsedWhere;
        }
        else
        {
            $return = "Error Notifier";
        }

        print_r($return);
    }

    /**
     * Wywolanie metod ktore konwertuja tablice na string do zlozenia query
     */
    private function patchQueryArray()
    {
        if ( !empty($this->parsedQuery['select']))
        {
            $this->reparseSelect();
        }
        if ( !empty($this->parsedQuery['from']))
        {
            $this->reparseFrom();
        }
        if ( !empty($this->parsedQuery['join']))
        {
            $this->reparseJoin();
        }
        if ( !empty($this->parsedQuery['where']))
        {
            $this->reparseWhere();
        }
    }

    public $reparsedSelect;
    public $reparsedFrom;
    public $reparsedJoin;
    public $reparsedWhere;

    /**
     * Tworzenie select z tablicy
     */
    private function reparseSelect()
    {
        $this->reparsedSelect = "SELECT \n\t";
        foreach($this->parsedQuery['select'] as $v)
        {
            if ($v['name'] == '*')
            {
                $this->reparsedSelect .= $v['name'] . ",";
            }
            else
            {
                $this->reparsedSelect .= " " . $this->aliases[$v['name']] . "." . $v['name'] . ",";
            }
        }
        $this->reparsedSelect = substr($this->reparsedSelect, 0, strlen($this->reparsedSelect)-1);
    }

    /**
     * Tworzenie from z tablicy
     */
    private function reparseFrom()
    {
        
        if ($this->aliases[$this->parsedQuery['from']['name']] != $this->parsedQuery['from']['alias'])
        {
            $this->aliasesMapper[$this->parsedQuery['from']['alias']] = $this->aliases[$this->parsedQuery['from']['name']];
        }
        $this->reparsedFrom = "\nFROM \n\t";
        $this->reparsedFrom .= " " . $this->parsedQuery['from']['name'] . " " . $this->aliases[$this->parsedQuery['from']['name']];//$this->parsedQuery['from']['alias'];
    }

    /**
     * Tworzenie join z tablicy
     */
    private function reparseJoin()
    {
        $this->reparsedJoin = "";
        foreach($this->parsedQuery['join'] as $k => $v)
        {
//            if ($this->aliases[$v['to']['name']] != $v['to']['alias'])
//            {
//                $this->aliasesMapper[$v['to']['alias']] = $this->aliases[$v['to']['name']];
//            }

            $this->reparsedJoin .= "\n" . strtoupper($v['type']) . " JOIN ";
            $this->reparsedJoin .= "\n\t" . (is_array($v['to']['name']) ? $v['to']['name'][0] : $v['to']['name']) . " " . $v['to']['alias'] . "\n";
            $this->reparsedJoin .= "ON";
            foreach ($v['on'] as $vv)
            {       
                //opcjonalne mapowanie aliasu uzytego w zapytaniu na zparsowane z bazy
//                if (isset($this->aliasesMapper[$vv[0]['alias']]))
//                {
//                    $vv[0]['alias'] = $this->aliasesMapper[$vv[0]['alias']];
//                }
//                if (isset($this->aliasesMapper[$vv[1]['alias']]))
//                {
//                    $vv[1]['alias'] = $this->aliasesMapper[$vv[1]['alias']];
//                }

                $this->reparsedJoin .= "\n\t" . $vv[0]['alias'] . "." . $vv[0]['column'] . " " . $vv['junction'] . " " . $vv[1]['alias'] . "." . $vv[1]['column'] . "\n";
                $this->reparsedJoin .= "AND";
            }

            $this->reparsedJoin = substr($this->reparsedJoin, 0, strlen($this->reparsedJoin)-3);
        }
    }

    /**
     * Tworzenie where z tablicy
     */
    private function reparseWhere()
    {
        $this->reparsedWhere = $this->dispatchedWhere;
    }

    /**
     * Pseudo __construct
     * 
     * @return <bool>
     */
    private function noConstructorNoFun()
    {
        if (isset($_POST['query']))
        {
            $this->sql_query = $_POST['query'];
            
            if (false !== ($tables = $this->registry['db']->showTables()))
            {
                foreach ($tables as $v)
                {
                    $this->aliasesTables[substr($v, 0, 1)][] = $v;
                }
                
                foreach ($this->aliasesTables as $k => $v)
                {
                    foreach ($v as $k1 => $v1)
                    {
                        $this->aliases[$v1] = $k . (!empty($k1) ? (string)$k1 : '');
                    }
                }
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Obrobka zapytania
     * dispatch czesci zapytania do zmiennych
     * parsowanie czesci
     * 
     * ! tymczasowo bez where !
     */
    private function sqlToArray()
    {
        //dispatch query
        $this->dispatchQuery();
        //musimy zparsowac wszystkie tabele do ktorych zapytujemy
        $this->parseFrom();
        $this->parseSelect();
        $this->parseWhere();
    }

    /**
     * Dispatcher zapytania na sekcje select, from, where
     */
    private function dispatchQuery()
    {
        foreach ($this->regexps as $kreg => $vreg)
        {
            $matchesarray = array();
            preg_match($vreg, $this->sql_query, $matchesarray);

            switch ($kreg)
            {
                case 'select':
                    $this->dispatchedSelect = $matchesarray[0];
                    break;
                case 'from':
                    $this->dispatchedFrom = $matchesarray[0];
                    break;
                case 'where':
                    $this->dispatchedWhere = $matchesarray[0];
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Parsowanie sekcji from (joiny)
     * 'and' traktowane sa jak 'on'
     */
    private function parseFrom()
    {
        $string = $this->dispatchedFrom;
        preg_match_all('/(SELECT|AND|FROM|ON|JOIN|\w+(\.|\s){0,1}\w+|\=)/ims', strtolower($string), $matches);// (SELECT|AND|FROM|ON|JOIN|\w+(\.|\s){0,1}\w+|\=)
        $onWriteFlag = false;
        $andWriteFlag = false;
        $onCount = 0;

        foreach ($matches[0] as $k=>$v)
        {
            if (in_array($v, array('from')))
            {
                $this->sectionFrom['from'] = array();
            }
            else if ( empty($this->sectionFrom['from']))
            {
                //rozdzielamy nazwe tabeli od aliasu
                if (false === ($temp = explode(' ', $v)))
                {
                    $temp[0] = $v;
                    $temp[1] = '';
                }
                    
                if ($this->aliases[$temp[0]] != $temp[1])
                {
                    $this->aliasesMapper[$temp[1]] = $this->aliases[$temp[0]];
                }
                
                $this->sectionFrom['from'] = array(
                    'name' => $temp[0],
                    'alias' => $this->aliases[$temp[0]]
                );
                
                $this->mainTable = $temp[0];
                unset($temp);
            }
            else if (in_array($v, array('where')))
            {
                //koniec jedyne co pozostaje to wyczyscic zmienna
                unset($joinOpenToWrite);
            }
            else if (in_array($v, array('join', 'inner join', 'left join', 'right join')))
            {
                //tu definijuemy countera dla tablicy on i and
                $onCount = 0;

                if (isset($this->sectionFrom['join']))
                {
                    $joinOpenToWrite = count($this->sectionFrom['join']);
                }
                else
                {
                    $joinOpenToWrite = 0;
                }

                switch ($v)
                {
                    case 'left join':
                        $this->sectionFrom['join'][]['type'] = 'left';
                        break;
                    case 'right join':
                        $this->sectionFrom['join'][]['type'] = 'right';
                        break;
                    case 'inner join':
                    case 'join':
                        $this->sectionFrom['join'][]['type'] = 'inner';
                        break;
                }
            }
            else if (isset($joinOpenToWrite))
            {
                if ( empty($this->sectionFrom['join'][$joinOpenToWrite]['to']))
                {
                    //rozdzielamy nazwe tabeli od aliasu
                    if (false === ($temp = explode(' ', $v)))
                    {
                        $temp[0] = $v;
                        $temp[1] = '';
                    }
                    
                    if ($this->aliases[$temp[0]] != $temp[1])
                    {
                        $this->aliasesMapper[$temp[1]] = $this->aliases[$temp[0]];
                    }
                    $this->sectionFrom['join'][$joinOpenToWrite]['to'] = array(
                        'name' => $temp[0],
                        'alias' => $this->aliases[$temp[0]]
                    );
                    
                    unset($temp);
                }
                else if (in_array($v, array('on','and')))
                {
                    $onWriteFlag = true;
                    $andWriteFlag = false;
                    $this->sectionFrom['join'][$joinOpenToWrite]['on'][++$onCount] = array();
//                    $this->sectionFrom['join'][$joinOpenToWrite]['on'] = array();
                }
//                else if (in_array($v, array('and')))
//                {
//                    $andWriteFlag = true;
//                    $onWriteFlag = false;
//                    $this->sectionFrom['join'][$joinOpenToWrite]['and'] = array();
//                }
                else if (true === $onWriteFlag)
                {
                    if (in_array($v, array('=', '!=')))
                    {
                        $this->sectionFrom['join'][$joinOpenToWrite]['on'][$onCount]['junction'] = $v;
                        continue;
                    }
                    
//                    print_r($v."\n\n");
//                    print_r($this->aliases);
//                    print_r($this->aliasesMapper);
                    
                    //rozdzielamy nazwe tabeli od aliasu
                    if (false === ($temp = explode('.', $v)))
                    {
                        $temp[1] = $v;
                        $temp[0] = '';
                    }
//                    print_r($temp[0] . " turned into: ");
                    //opcjonalne mapowanie aliasu uzytego w zapytaniu na zparsowane z bazy
                    if (isset($this->aliasesMapper[$temp[0]]))
                    {
                        $temp[0] = $this->aliasesMapper[$temp[0]];
                    }
//                    print_r($temp[0] . "\n\n----------------------------------------\n\n");
                    
                    switch(strlen($temp[0]))
                    {
                        case (1 < strlen($temp[0])):
                            $shortAlias = substr($temp[0], 0, 1);
                            $aliasNumber = (int)substr($temp[0], 1);
                            break;
                        default:
                            $shortAlias = $temp[0];
                            $aliasNumber = 0;
                            break;
                    }

                    $from = $this->aliasesTables[$shortAlias][$aliasNumber];
                            
                    $this->sectionFrom['join'][$joinOpenToWrite]['on'][$onCount][] = array(
                        'column' => $temp[1],
                        'from' => $from,
                        'alias' => $temp[0]
                    );
                    
                    unset($temp,$aliasNumber,$shortAlias);
                }
//                else if (true === $andWriteFlag)
//                {
//                    if (in_array($v, array('=', '!=')))
//                    {
//                        $this->sectionFrom['join'][$joinOpenToWrite]['and']['junction'] = $v;
//                        continue;
//                    }
//                    $temp = explode('.', $v);
//                    $this->sectionFrom['join'][$joinOpenToWrite]['and'][] = array(
//                        'column' => isset($temp[1]) ? $temp[1] : '',
//                        'from' => isset($temp[0]) ? $temp[0] : ''
//                    );
//                    unset($temp);
//                }
            }
        }
        $this->parsedQuery = $this->sectionFrom;
    }

    /**
     * Parsowanie sekcji select
     */
    private function parseSelect()
    {
        $string = $this->dispatchedSelect;
        //some parsing code goes here
        $matchesarray = array();
        preg_match_all('/(\w+(\.|\s){0,1}\w+|\*)/ims', strtolower($string), $matches);

        foreach ($matches[0] as $k=>$v)
        {
            if (in_array($v, array('select')))
            {
                $this->sectionSelect['select'] = array();
            }
            else if ( isset($this->sectionSelect['select']) and in_array($v, array('*')))
            {
                $this->sectionSelect['select'][] = array('name' => '*');
            }
            else if ( isset($this->sectionSelect['select']) and !in_array($v, array('from')))
            {
                $temp = explode('.', $v);
                $this->sectionSelect['select'][] = array(
                    'alias' => isset($temp[0]) ? $temp[0] : '',
                    'name' => isset($temp[1]) ? $temp[1] : ''
                );
                unset($temp);
            }
        }
        $this->parsedQuery['select'] = $this->sectionSelect['select'];
    }

    /**
     * @todo: Parsowanie sekcji where
     */
    private function parseWhere()
    {
        //some parsing code goes here
        $this->parsedQuery['where'] = $this->dispatchedWhere;
    }

    public $coords = array();
    public $coordsCounted = array();
    public $metrics = array();

    /**
     * Obliczanie struktury i koordynatow w ukladzie kartezjanskim
     * metoda inicjujaca countLevels oraz countMetrics
     */
    public function countCords ()
    {
        $this->coords[$this->parsedQuery['from']['name']]['level'] = 0;
        $this->coords[$this->parsedQuery['from']['name']]['alias'] = $this->parsedQuery['from']['alias'];
        $this->coords[$this->parsedQuery['from']['name']]['children'] = $this->countLevels($this->parsedQuery['from']['name']);

        $this->coordsCounted = $this->coords;
        $this->countMetrics($this->coords, $this->coordsCounted);
    }

    /**
     * Tworzenie hierarhii joinow - zapis do tablicy
     *
     * @param <string> $parent
     * @param <int> $level
     * @return <array_or_false>
     */
    private function countLevels($parent, $level = 0)
    {
        //wszystkie joiny w zapytaniu
        if (empty($this->parsedQuery['join']))
        {
            return false;
        }
        foreach($this->parsedQuery['join'] as $table => $join_array)
        {
            //warunki dla joina (on + and)
            foreach ($join_array['on'] as $varr)
            {
                // kolumny z warunku
                foreach($varr as $k => $v)
                {
                    // jezeli ktorys z joinow odpowiada parentowi
                    if( is_int($k) and $parent == $v['from'])
                    {
                        if ($this->idAndAlias[1] != $v['alias'])
                        {
                            if ( !isset($cords[(1 == $k ? $varr[0]['from'] : $varr[1]['from'])]))
                            {
                                $cords[(1 == $k ? $varr[0]['from'] : $varr[1]['from'])] = array(
                                    'column' => 1 == $k ? $varr[0]['column'] : $varr[1]['column'],
                                    'from' => 1 == $k ? $varr[0]['from'] : $varr[1]['from'],
                                    'alias' => 1 == $k ? $varr[0]['alias'] : $varr[1]['alias'],
                                    'level' => $level+1,
                                );
                            }
                            // jezeli istnieje juz taki element to dodajemy do tablicy joina
                            $cords[(1 == $k ? $varr[0]['from'] : $varr[1]['from'])]['join'][] = array(
                                    'join_name' => 1 == $k ? $varr[1]['from'] : $varr[0]['from'],
                                    'join_column' => 1 == $k ? $varr[1]['column'] : $varr[0]['column'],
                                    'join_alias' => 1 == $k ? $varr[1]['alias'] : $varr[0]['alias']
                                );

                            $nextOnesToBeParent[(1 == $k ? $varr[0]['from'] : $varr[1]['from'])][] = $table;
                        }
                    }
                }
            }
        }
        
        if ( isset($nextOnesToBeParent) and is_array($nextOnesToBeParent))
        {
            $nextNames = array();
            foreach ($nextOnesToBeParent as $nextName => $elementsToDelArray)
            {
                foreach($elementsToDelArray as $elementsToDel)
                {
                    unset($this->parsedQuery['join'][$elementsToDel]);
                }
                $nextNames[] = $nextName;
            }
            
            foreach ($nextNames as $nextOneToBeParent)
            {
                if (false !== ($childern = $this->countLevels($nextOneToBeParent, $level+1)))
                {
                    $cords[$nextOneToBeParent]['children'] = $childern;
                }
            }
        }

        if ( !empty($cords))
        {
            return $cords;
        }
        else
        {
            return false;
        }
    }

    private function countMetrics($coords, &$countedArray, $parentsParts = 1, $angle_start = 0, $angle_end = 360)
    {
        $part = 0;
        $angle_of_single_part = ($angle_end - $angle_start) / $parentsParts;

        foreach ($coords as $k => $vk)
        {
            if ($vk['level'] != 0)
            {
                $countedArray[$k]['coords'] = $this->cartesian($angle_of_single_part, $angle_start + $angle_of_single_part*$part, $angle_start + $angle_of_single_part*($part+1), $vk['level']);
            }

            if ( isset($vk['children']) and is_array($vk['children']))
            {
                $this->countMetrics($vk['children'], $countedArray[$k]['children'], count($vk['children']), $angle_start + $angle_of_single_part*$part, $angle_start + $angle_of_single_part*($part+1));
            }
            $part++;
        }
    }
    
    // zmienne potrzebne do obliczania elementow
    public $stMx = 300; //calosciowe, poziome przesuniecie ukladu (srodka)
    public $stMy = 200; //calosciowe, pionowe przesuniesie ukladu (srodka)
    public $rGap = 6; //przestrzen miedzy okregami
    public $rCircleS = 10; // maly luk
    public $rCircleL = 30; // duzy luk
    public $radConv = 0.017453292519943295;

    /**
     *Obliczanie elementu w układzie kartezjanskim
     *
     * @param <numeric> $angle_single
     * @param <numeric> $angle_start
     * @param <numeric> $angle_end
     * @param <int> $level
     * @return <array>
     */
    private function cartesian($angle_single, $angle_start = 0, $angle_end = 360, $level)
    {
        $cords = array();
        
        $cords['rS'] = ($this->rGap * ($level)) + ($this->rCircleS * ($level-1)) + ($this->rCircleL * ($level));
        $cords['rM'] = ($this->rGap * ($level)) + ($this->rCircleS * ($level)) + ($this->rCircleL * ($level));
        $cords['rL'] = ($this->rGap * ($level)) + ($this->rCircleS * ($level)) + ($this->rCircleL * ($level+1));

        $cords['start'] = array
        (
            'xS' => $cords['rS'] * cos(deg2rad($angle_start)) + $this->stMx,
            'yS' => $cords['rS'] * sin(deg2rad($angle_start)) + $this->stMy,
            'xM' => $cords['rM'] * cos(deg2rad($angle_start)) + $this->stMx,
            'yM' => $cords['rM'] * sin(deg2rad($angle_start)) + $this->stMy,
            'xL' => $cords['rL'] * cos(deg2rad($angle_start)) + $this->stMx,
            'yL' => $cords['rL'] * sin(deg2rad($angle_start)) + $this->stMy
        );
        $cords['end'] = array
        (
            'xS' => $cords['rS'] * cos(deg2rad($angle_end)) + $this->stMx,
            'yS' => $cords['rS'] * sin(deg2rad($angle_end)) + $this->stMy,
            'xM' => $cords['rM'] * cos(deg2rad($angle_end)) + $this->stMx,
            'yM' => $cords['rM'] * sin(deg2rad($angle_end)) + $this->stMy,
            'xL' => $cords['rL'] * cos(deg2rad($angle_end)) + $this->stMx,
            'yL' => $cords['rL'] * sin(deg2rad($angle_end)) + $this->stMy
        );

        return $cords;
    }
}
?>