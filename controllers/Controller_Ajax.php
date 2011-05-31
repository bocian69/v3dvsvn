<?php 

class Controller_Ajax Extends Controller_Base {
	
	/**
	 * 
	 * Zmienna przechowująca argumenty z GET
	 * @var array
	 */
	
	var $args = array();
	
	
	/**
	 * 
	 * Ustawia argumenty przekazane z GET sformatowane przez router w postaci klucz=>wartosc
	 * @param $args
	 */
	
	function setArgs($args) {
		$this->args = $args;
	}
	
	
	/**
	 * 
	 * Starter
	 */
	
	function index() {
		
		//jesli wywolanie nie jest po ajaxie to przekierowujemy na strone błędu i pokazujemy jakie parametry zostały przekazane
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') 
        {
			$errors = array('error_msg' => array('Wywołanie kontrolera ajaxowego z przeglądarki'));
			$errors['error_msg'][] = print_r($this->args, true);
			$this->registry['template']->assign('errors', $errors);
			$this->registry['template']->display('error.tpl');
			die;
		}
		if (isset($_POST['action'])) 
        {
			if( method_exists('Controller_Ajax', $_POST['action']) === true)
            {
				call_user_func(array(&$this, $_POST['action']));
				die;
		    }
		}
		echo 'Błąd! Żądana metoda nie istnieje.';
		die;
	}
	
	function getTableInfo()
    {
		if (!empty($_POST['table'])) 
            {
			$info = $this->registry['db']->tableInfo($_POST['table']);
			echo json_encode($info);
		}
	}
	
	function showRelations()
    {
		$info = $this->registry['db']->showRelations();
		echo json_encode($info);
	}
    
    function getTablesColumns()
    {
		$columns1 = $this->registry['db']->showColumns($_POST['table1']);
		$columns2 = $this->registry['db']->showColumns($_POST['table2']);
        
        $html_returned = "<center>pick join on </center><br />";
        
        //table 1
        $html_returned .= '<select class="JSdropOf___' . $_POST['table1'] . ' JSconstraintOfTable1SelectedToPickConstraint">';
        foreach ($columns1 as $options1)
        {
            $html_returned .= '<option value="' . $options1['field'] . '">' . $options1['field'] . ' ('. $options1['type'] . ')</option>';
        }
        $html_returned .= '</select>';

        //joiner
        $html_returned .= '<select class="JSofOf___' . $_POST['table1'] . '___' . $_POST['table1'] . ' JSjunctionOfTablesSelectedToPickConstraint">';
        $html_returned .= '<option value="=">=</option>';
        $html_returned .= '<option value="!=">!=</option>';
        $html_returned .= '<option value="<>">&lt;&gt;</option>';
        $html_returned .= '</select>';
        
        //table2
        $html_returned .= '<select class="JSdropOf___' . $_POST['table2'] . ' JSconstraintOfTable2SelectedToPickConstraint">';
        foreach ($columns2 as $options2)
        {
            $html_returned .= '<option value="' . $options2['field'] . '">' . $options2['field'] . ' ('. $options2['type'] . ')</option>';
        }
        $html_returned .= '</select>';
        
        $html_returned .= '<p><a href="#" class="JSmarkConstraintChoises" style="float:left">choose this constraint</a>';
        $html_returned .= '<a href="#" class="JScancelConstraintChoises" style="color:red;float:right">cancel</a></p>';
        
        print_r($html_returned);
    }
}
?>