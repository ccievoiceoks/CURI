<?php
//ini_set('display_errors', 'On');
header('Content-Type: text/xml');
$contresp = '<?xml encoding="UTF-8" version="1.0"?><Response><Result><Decision>Permit</Decision><Status></Status><Obligations><Obligation FulfillOn="Permit" ObligationId="urn:cisco:xacml:policy-attribute"><AttributeAssignment AttributeId="Policy:simplecontinue"><AttributeValue DataType="http://www.w3.org/2001/XMLSchema#string">&lt;cixml ver="1.0"&gt;&lt;continue&gt;&lt;/continue&gt; &lt;/cixml&gt;</AttributeValue></AttributeAssignment></Obligation></Obligations></Result></Response>';

$denyresp = '<?xml encoding="UTF-8" version="1.0"?><Response><Result><Decision>Deny</Decision><Status></Status><Obligations><Obligation FulfillOn="Deny" ObligationId="urn:cisco:xacml:policy-attribute"><AttributeAssignment AttributeId="Policy:simplecontinue"><AttributeValue DataType="http://www.w3.org/2001/XMLSchema#string">&lt;cixml ver="1.0"&gt;&lt;reject&gt;&lt;/reject&gt; &lt;/cixml&gt;</AttributeValue></AttributeAssignment></Obligation></Obligations></Result></Response>';

//checking input type
if (file_get_contents("php://input")=="")
{
 //exit;
}

$dom = new DOMDocument;
$dom->loadXML( file_get_contents("php://input") );

$xp = new DOMXPath($dom);
$xp->registerNamespace("o", "urn:oasis:names:tc:xacml:2.0:context:schema:os");
$nodes = $xp->query("//o:Attribute[@AttributeId='urn:Cisco:uc:1.0:callingnumber']/o:AttributeValue");

foreach ($nodes as $n) {
     $currentid=$n->nodeValue;    
}

include("./inc.conn.php");

$sql = "SELECT * from caller WHERE number='$currentid'";
$req = mysql_query($sql) or die("Sorry, SQL error");
$res = mysql_num_rows($req);

if($res == 1)
{ 
 $data = mysql_fetch_assoc($req);

 if(!$data['blocked'])
 {
  echo '<?xml encoding="UTF-8" version="1.0"?><Response><Result><Decision>Permit</Decision><Status></Status><Obligations><Obligation FulfillOn="Permit" ObligationId="urn:cisco:xacml:policy-attribute"><AttributeAssignment AttributeId="Policy:simplecontinue"><AttributeValue DataType="http://www.w3.org/2001/XMLSchema#string">&lt;cixml ver="1.0"&gt;&lt;continue&gt;&lt;modify callingname="'.$data['name'].'"/&gt;&lt;/continue&gt; &lt;/cixml&gt;</AttributeValue></AttributeAssignment></Obligation></Obligations></Result></Response>';
 }
 else
 {
  echo $denyresp;
 }
}
else
{
 echo $contresp;
}

?>
