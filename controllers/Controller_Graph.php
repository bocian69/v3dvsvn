<?

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

    public function getCoords()
    {
        if (false !== $this->noConstructorNoFun())
        {
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
     * Pseudo __construct
     * 
     * @return <bool>
     */
    private function noConstructorNoFun()
    {
        if (isset($_POST['query']))
        {
            $this->sql_query = $_POST['query'];
            $this->sqlToArray();
            
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
//            $this->parseWhere($matchesarray);

//        $this->parseCirclesStructure();
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
        preg_match_all('/(\w+(\.|\s){0,1}\w+|\=)/ims', strtolower($string), $matches);
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
                $temp = explode(' ', $v);
                $this->sectionFrom['from'] = array(
                    'name' => isset($temp[0]) ? $temp[0] : '',
                    'alias' => isset($temp[1]) ? $temp[1] : ''
                );
                $this->aliases[(isset($temp[1]) ? $temp[1] : '')] = isset($temp[0]) ? $temp[0] : '';
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
                    $temp = explode(' ', $v);
                    $this->sectionFrom['join'][$joinOpenToWrite]['to'] = array(
                        'name' => isset($temp[0]) ? $temp[0] : '',
                        'alias' =>isset($temp[1]) ? $temp[1] : ''
                    );
                    $this->aliases[(isset($temp[1]) ? $temp[1] : '')] = isset($temp[0]) ? $temp[0] : '';
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

                    $temp = explode('.', $v);
                    $this->sectionFrom['join'][$joinOpenToWrite]['on'][$onCount][] = array(
                        'column' => isset($temp[1]) ? $temp[1] : '',
                        'from' => (isset($temp[0]) and isset($this->aliases[$temp[0]])) ? $this->aliases[$temp[0]] : ''
                    );

                    unset($temp);
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
                $this->sectionSelect['select']['select'] = array();
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
        $parsed_array = array();
        //some parsing code goes here
        $this->parsedQuery['where'] = $parsed_array;
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
        $this->coords[$this->parsedQuery['from']['name']]['children'] = $this->countLevels($this->parsedQuery['from']['name']);
        
        $this->coordsCounted = $this->coords;
        $this->countMetrics($this->coords, $this->coordsCounted);
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

    /**
     * Tworzenie hierarhii joinow - zapis do tablicy
     *
     * @param <string> $parent
     * @param <int> $level
     * @return <array_or_false>
     */
    private function countLevels($parent, $level = 0)
    {
        foreach($this->parsedQuery['join'] as $table => $join_array)
        {
            foreach ($join_array['on'] as $karr => $varr)
            {
                foreach($varr as $k => $v)
                {
                    if( is_int($k) and $parent == $v['from'])
                    {
                        if ( !isset($cords[(1 == $k ? $varr[0]['from'] : $varr[1]['from'])]))
                        {
                            $cords[(1 == $k ? $varr[0]['from'] : $varr[1]['from'])] = array(
                                'column' => 1 == $k ? $varr[0]['column'] : $varr[1]['column'],
                                'from' => 1 == $k ? $varr[0]['from'] : $varr[1]['from'],
                                'level' => $level+1,
                            );
                            $nextOneToBeParent = 1 == $k ? $varr[0]['from'] : $varr[1]['from'];
                        }
                        $cords[(1 == $k ? $varr[0]['from'] : $varr[1]['from'])]['join'][] = array(
                                'join_name' => 1 == $k ? $varr[1]['from'] : $varr[0]['from'],
                                'join_column' => 1 == $k ? $varr[1]['column'] : $varr[0]['column']
                            );
                    }
                }
            }

            if ( !empty($nextOneToBeParent))
            {
                unset($this->parsedQuery['join'][$table]);

                if (false !== ($childern = $this->countLevels($nextOneToBeParent, $level+1)))
                {
                    $cords[$nextOneToBeParent]['children'] = $childern;
                }
                unset($nextOneToBeParent);
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

    // zmienne potrzebne do obliczania elementow
    public $stMx = 300; //calosciowe, poziome przesuniecie ukladu (srodka)
    public $stMy = 200; //calosciowe, pionowe przesuniesie ukladu (srodka)
    public $rGap = 5; //przestrzen miedzy okregami
    public $rCircleS = 5; // maly luk
    public $rCircleL = 20; // duzy luk
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