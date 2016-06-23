<?php
$NumSite = 'MAR484';
$Password = 'je@wbO96';
$Amount = '1250';
$Devise = 'TND';
$orderId = date('ymdHis');
$signture = sha1($NumSite.$Password.$orderId.$Amount.$Devise);
?>
<FORM name="paiment" method="POST" action="https://preprod.gpgcheckout.com/Paiement_test/Validation_paiement.php" >
 <input type="hidden" name="NumSite" value="<?php echo $NumSite;?>"><br /><br />
 <input type="hidden" name="Password" value="<?php echo md5($Password);?>"><br /><br />
 <input type="hidden" name="orderID" value="<?php echo $orderId ;?>"><br /><br />
 <input type="hidden" name="Amount" value="<?php echo $Amount;?>"><br /><br />
 <input type="hidden" name="Currency" value="<?php echo $Devise;?>"><br /><br />
 <input type="hidden" name="Language" value="fr"><br /><br />
 <input type="hidden" name="EMAIL" value="contact@gpgcheckout.com"><br /><br />
 <input type="hidden" name="CustLastName" value="romdhani"><br /><br />
 <input type="hidden" name="CustFirstName" value="amir"><br /><br />
 <input type="hidden" name="CustAddress" value="sousse"><br /><br />
 <input type="hidden" name="CustZIP" value=""><br /><br />
 <input type="hidden" name="CustCity" value=""><br /><br />
 <input type="hidden" name="CustCountry" value=""><br /><br />
 <input type="hidden" name="CustTel" value="21460628"><br /><br />
 <input type="hidden" name="PayementType" value="1"><br /><br />
 <input type="hidden" name="MerchandSession" value=""><br /><br />
 <input type="hidden" name="orderProducts" value="2 * paque 2"><br /><br />ADS MANIA
 <input type="hidden" name="signature" value="<?php echo $signture;?>"><br /><br />
 <input type="hidden" name="AmountSecond" value=""><br /><br />
 <input type="hidden" name="vad" value="981300003"><br /><br />
 <input type="hidden" name="Terminal" value="001"><br /><br />
 <input type="hidden" name="TauxConversion" value=" "><br /><br />
 <input type="hidden" name="BatchNumber" value=" "><br /><br />
 <input type="hidden" name="MerchantReference" value=" "><br /><br />
 <input type="hidden" name="Reccu_Num" value=""><br /><br />
 <input type="hidden" name="Reccu_ExpiryDate " value=""><br /><br />
 <input type="hidden" name="Reccu_Frecuency " value=" "><br /><br />
 <input type="submit" name= "Valider" value="Valider" >
</FORM>