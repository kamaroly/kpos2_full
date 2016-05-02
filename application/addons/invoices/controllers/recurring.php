<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package    Kinvoice
 * @subpackage Invoices
 * @name       Invoices
 * @category   Controller
 * @author     Derrek 
 * @author     Kamaro Lambert
 * @copyright  Kamaro Lambert
 */

include(APPPATH.'controllers/secure_area.php');
class Recurring extends Secure_area {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('mdl_invoices_recurring');
    }

    /**
     * @author Kamaro Lambert
     * @method to enter the recurring invoices section
     * @param numeric $page
     */
    public function index($page = 0)
    {
        $this->mdl_invoices_recurring->paginate(site_url('invoies/recurring'), $page);
        $recurring_invoices = $this->mdl_invoices_recurring->result();
        
        $this->layout->set('recur_frequencies', $this->mdl_invoices_recurring->recur_frequencies);
        $this->layout->set('recurring_invoices', $recurring_invoices);
        $this->layout->buffer('content', 'invoices/index_recurring');
        $this->layout->render();
    }

    public function stop($invoice_recurring_id)
    {
        $this->mdl_invoices_recurring->stop($invoice_recurring_id);
        redirect('invoices/recurring/index');
    }
    
    public function delete($invoice_recurring_id)
    {
        $this->mdl_invoices_recurring->delete($invoice_recurring_id);
        redirect('invoices/recurring/index');
    }

}

?>