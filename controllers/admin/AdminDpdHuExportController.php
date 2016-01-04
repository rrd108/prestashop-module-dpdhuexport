<?php
/**
 * 2016 rrd
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * DISCLAIMER
 *
 * You must not modify, adapt or create derivative works of this source code
 * without the permission of the author
 *
 * @author    rrd <rrd@webmania.cc>
 * @copyright 2016 rrd
 * @license   http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @version   0.0.1
 *
 */

class AdminDpdHuExportController extends ModuleAdminController
{
    private $number_from;
    private $number_to;
    
    public function __construct()
    {
        // Set variables
        $this->table = 'order_invoice';
        $this->className = 'Dpdhuexport';
        $this->bootstrap = true;
        $this->display = 'view';
        
        // Call of the parent constructor method
        parent::__construct();
    }
    
	public function init()
    {
        $this->number_from = Tools::getValue('number_from') ? Tools::getValue('number_from') : false;
        $this->number_to = Tools::getValue('number_to') ? Tools::getValue('number_to') : false;
        if ($this->number_to && $this->number_from > $this->number_to) {
            $_n = $this->number_from;
            $this->number_from = $this->number_to;
            $this->number_to = $_n;
        }

        $this->postProcess();
        
        $this->context->smarty->assign(
			[
			 'number_from' => $this->number_from,
			 'number_to' => $this->number_to,
			]);
        
        parent::init();
    }
    
    public function renderView()
	{   
        $tpl = $this->context->smarty->createTemplate(
			dirname(__FILE__) . '/../../views/templates/admin/export.tpl',
			$this->context->smarty
		);
        return $tpl->fetch();
	}
    
	public function postProcess()
	{
		if (Tools::isSubmit('submitOrderNumber'))
		{
			if (!Validate::isInt($this->number_from))
				$this->errors[] = $this->l('Invalid "From" number');

			if (!Validate::isInt($this->number_to))
				$this->errors[] = $this->l('Invalid "To" number');

			if (!count($this->errors))
			{
				$invoices = $this->getByNumberInterval($this->number_from, $this->number_to);
                if (count($invoices)) {
                    //generate XML
					$this->generateCSV($invoices);
				}
                else {
                    $this->errors[] = $this->l('No invoice has been found for this period.');
                }
			}
		}
		else
        {
			parent::postProcess();          
        }
    }
    
    private function getByNumberInterval($number_from, $number_to)
	{
		//this function is not there in OrderInvoice class, so I implemented by my own

        $order_invoice_list = Db::getInstance()->executeS('
			SELECT oi.*
			FROM `'._DB_PREFIX_.'order_invoice` oi
			LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = oi.`id_order`)
			WHERE oi.id_order <= \''.pSQL($number_to).'\'
			AND oi.id_order >= \''.pSQL($number_from).'\'
			'.Shop::addSqlRestriction(Shop::SHARE_ORDER, 'o').'
			ORDER BY oi.date_add ASC
		');

		return ObjectModel::hydrateCollection('OrderInvoice', $order_invoice_list);
	}
    
    private function generateCSV($collection)
    {
        // clean buffer
		if (ob_get_level() && ob_get_length() > 0)
			ob_clean();
		$this->getList($this->context->language->id);
		if (!count($this->_list))
			return;

		header('Content-type: text/csv');
		header('Content-Type: application/force-download; charset=ISO-8859-2');
		header('Cache-Control: no-store, no-cache');
		header('Content-disposition: attachment; filename="'.$this->className.'_'.date('Y-m-d_His').'.csv"');

		$megrendelok = [];
		foreach ($collection as $i => $orderInvoice) {
			$order = new Order((int)$orderInvoice->id_order);
			$customer = new Customer((int)$order->id_customer);
			$cart = new CartCore((int) $order->id_cart);

			$megrendelok[$i] = [
				'order' => $order,
				'order_invoice' => $orderInvoice,
				'customer' => $customer,
				'weight' => $cart->getTotalWeight(),
				'address' => new Address((int)$order->id_address_invoice),
			];
		}
		//var_dump($megrendelok);
		
		$this->context->smarty->assign(
			[
			 'megrendelok' => $megrendelok
			]
		);

		$this->layout = dirname(__FILE__) . '/../../views/templates/admin/csv.tpl';
    }
}
