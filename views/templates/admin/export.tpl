{*
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
*}

<div class="panel">
	<form method="post">
		<fieldset>
			<legend>{l s='CSV export by order number' mod='dpdhuexport'}</legend>

			<label>{l s='From' mod='dpdhuexport'}: </label><sup>*</sup>
			<div class="margin-form">
				<input type="text" value="{$number_from}" name="number_from" size="8">
			</div>
			<sup>{l s='You also can list any order numbers separated by commas' mod='dpdhuexport'}</sup>

			<div class="clear"></div>

			<label>{l s='To' mod='dpdhuexport'}: </label><sup>*</sup>
			<div class="margin-form">
				<input type="text" value="{$number_to}"  name="number_to" size="8">
			</div>

			<div class="clear"></div>

			<div class="margin-form">
				<input
					type="submit"
					class="button"
					value="CSV export"
					id="submitOrderNumber"
					name="submitOrderNumber">
			</div>

			<div class="small"><sup>*</sup> {l s='Required field' mod='dpdhuexport'}</div>
		</fieldset>

	</form>
</div>

<div class="panel">
	<h3>{l s='Support' mod='dpdhuexport'}</h3>
	<p>
		{l s='Need help or have question?' mod='dpdhuexport'} <a href="mailto:rrd@webmania.cc">{l s='Write to us!' mod='dpdhuexport'}</a>
	</p>
    <p>
        <a href="http://webmania.cc/tag/prestashop/">
            <img src="http://webmania.cc/prestashop/navexport-logo.png">
        </a>
    </p>
</div>
