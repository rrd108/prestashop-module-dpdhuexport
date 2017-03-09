<?php
/**
 * 2016 - 2017 rrd
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
 * @copyright 2016 - 2017 rrd
 * @license   http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @version   0.0.2
 *
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Dpdhuexport extends Module
{
    public function __construct()
    {
        $this->name = 'dpdhuexport';
        $this->tab = 'export';
        $this->version = '0.0.2';
        $this->author = 'rrd';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('DPD Export');
        $this->description = $this->l('Export orders to CSV format for DPD import');

    }

    public function install()
    {
        //install admin tab
        if (!$this->installTab('AdminOrders', 'AdminDpdHuExport', 'DPD Export')) {
            return false;
        }

        return parent::install();
    }

    public function uninstall()
    {
        //uninstall admin tab
        if (!$this->uninstallTab('AdminDpdHuExport')) {
            return false;
        }

        return parent::uninstall();
    }

    public function installTab($parent, $class_name, $name)
    {
        //create new admin tab
        $tab = new Tab();
        $tab->id_parent = (int)Tab::getIdFromClassName($parent);
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }
        $tab->class_name = $class_name;
        $tab->module = $this->name;
        $tab->active = 1;
        return $tab->add();
    }

    public function uninstallTab($class_name)
    {
        $id_tab = (int)Tab::getIdFromClassName($class_name);
        $tab = new Tab((int)$id_tab);
        return $tab->delete();
    }
}
