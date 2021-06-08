{literal} 
	<style>	
    table {
				width: 100%;
				font-family: helvetica;
				line-height: 5mm;
				border-collapse: collapse;
				
			}
		
			h2  { margin: 0; padding: 0; }  
			.border th {
				border: 1px solid #e0e0e0;
				color: black;
				background: #e0e0e0;
				padding: 5px;
				font-weight: normal;
				font-size: 14px;
				text-align: center; 
			
				}
			.border td {
				border: 1px solid #CFD1D2;
				padding: 5px 10px;
				text-align: center;
			}
			.row_content { width: 10%; }
			.qte { width: 10%; }
			.clt { 
				width: 73%;
				border :1px dashed #060606; 
				padding:19px 0px 25px 16px; 
				border-radius: 5px;
			 } 
             .clt p {
                 line-height:0px;
             }
            
			.cart_title { width: 50%; }
			.adr_livraison { width: 50%; }
			.footer{
				text-align:center;
			}
			table.table_ft_bordered{
				border: 1px solid black;
			}
			.last_p, .img_shop{
				margin-bottom:18px !important;
			}
		.desc{
			color:#060606;  
			font-size:12px;
			font-weight:bold;
			padding-left:30px;
		}
		.tel ,.fax{
			color:#060606;  
			font-size:13px;
		}
		.fax{
			margin-left:15px;
		}
        .livraison{
            padding-left:75px;
        }
        .date_devis{
            margin-top:60px;
            text-align:center;
            font-size:19px;
        }
        .adresse1{
            font-size :11px;
        }
</style>		
{/literal} 
<table style="padding:20px;">
    <tr>
        <td valign="top" width="20%">
            <img class="" src="{$footer['logo_path']}" width="150"/>
            <p class="desc">
                {$shop['desc']}
            </p>
            <span class="tel">{if isset($shop['Tel']) && $shop['Tel'] neq "" } {l s='Phone :' mod='eg_printcart'}  {$shop['Tel']|nl2br} {/if}</span> <span class="fax">{if isset($shop['Fax']) && $shop['Fax'] neq ""} {l s='Fax :' mod='eg_printcart'} {$shop['Fax']|nl2br} {/if} </span>
           <p class="date_devis"> {l s='Cart from ' mod='eg_printcart'} {$date_devis} </p>
        </td>
        <td valign="top"  width="80%" >
            <table style="vertical-align: top;"  class="livraison"> 
          
		{if Context::getContext()->customer->isLogged()}
                    <tr>
                        <td class="clt">

                            {l s='shipping at :' mod='eg_printcart'}  
                            <p>{$customer['firstname']}  {$customer['lastname']}</p> 
                            <p>{$customer['mail']|nl2br}</p>
                            {if isset($adress[0]) && $adress[0]}
                                <p class="adresse1">{$adress[0]["address1"]|nl2br}</p>  
                                <p>{$adress[0]["address2"]|nl2br}</p>  
                                <p>{$adress[0]["postcode"]|nl2br}  {$adress[0]["city"]} </p> 
                                <p>{$adress[0]["country"]|nl2br} </p>
                                <p>{$adress[0]["phone"]|nl2br} </p> 
                            {/if}    
                            
                        </td>
                    </tr>

                {/if}	

            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table style="margin-top: 90px; padding-right:30px;" class="border" width="100%">
                <thead>
                    <tr>
                        <th>{l s='N/REF:' mod='eg_printcart'}</th>
                        <th>{l s='Product Name' mod='eg_printcart'}</th>
                        <th>{l s='Quantity' mod='eg_printcart'}</th>
                        {if Context::getContext()->customer->isLogged()}<th>{l s='Net price' mod='eg_printcart'}</th>{/if}
                       {if Context::getContext()->customer->isLogged()} <th>{l s='Amount' mod='eg_printcart'}</th>{/if}

                    </tr>
                </thead>
                <tbody>
                    {foreach from=$products item=$product }
                    <tr>
                            <td>
                            {$product['reference']}  
                            </td>
                            <td>
                            {$product['name'] } 
                                    
                            {if isset($product['attributes'])}
                            
                                {$product['attributes']}
                            {/if}
                            </td>
                            <td>
                            {$product['cart_quantity']} 
                            </td>
                            {if Context::getContext()->customer->isLogged()}
                            <td>
                            {if isset($product["price"]) && $product["price"] neq "" }
                            {Tools::displayPrice($product['price'], Context::getContext()->language->id)} 
                            {/if}
                            </td>
                             {/if}
                           {if Context::getContext()->customer->isLogged()}
                            <td>
                            {Tools::displayPrice($product['total'], Context::getContext()->language->id)}
                            </td>
                            {/if}
                        </tr>
                    {/foreach} 
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    {if Context::getContext()->customer->isLogged()}
    <tr>
        <td colspan="2">
            <table class="border" width="100%">
                <thead>
                    <tr>
                        <th colspan="2">{l s='Resum' mod='eg_printcart'}</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> {l s='Number of articles in the cart' mod='eg_printcart'} </td>
                        <td>
                        {$total}
                        </td>
                    </tr>
                    <tr>
                        <td>{l s='Total products without tax' mod='eg_printcart'}</td>
                        <td>
                         {Tools::displayPrice($cart_details['total_products'], Context::getContext()->language->id)}     
                          </td>
                    </tr>
                     <tr>
                        <td>{l s='Delivery ' mod='eg_printcart'} </td>
                        
                        <td>{Tools::displayPrice($cart_details['total_shipping_tax_exc'], Context::getContext()->language->id)}</td>
                    </tr>
                    <tr>
                        <td>{l s='Total tax ' mod='eg_printcart'} </td>
                        <td>
                        {Tools::displayPrice($cart_details['total_tax'], Context::getContext()->language->id)}  
                    
                        </td>
                    </tr>
                   
                    <tr>
                        <td> {l s='Total products with tax ' mod='eg_printcart'} </td>
                        <td>

                        {Tools::displayPrice($cart_details['total_products_wt'], Context::getContext()->language->id)}
                      
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    {/if}
</table>

<page_footer class="footer">


    <p> {$footer['footer_capital']} <br/>
    {$footer['footer_siege_social']} <br/>
	{$footer['PRINTCART_SIREL']}</p>
    <p>&nbsp;</p>
</page_footer>