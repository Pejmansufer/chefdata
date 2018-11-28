<?php 
class Chefsdeal_FinancingProgram_IndexController extends Mage_Core_Controller_Front_Action{
    public function indexAction(){ //this will display the form
        $this->loadLayout();
        $this->_initLayoutMessages('core/session'); //this will allow flash messages
        $this->renderLayout();
    }
    public function sendAction(){ //handles the form submit
        $post = $this->getRequest()->getPost();
        //do something with the posted data
		
		$sales_contact 			= $this->getRequest()->getPost("sales_contact");
		$business_name 			= $this->getRequest()->getPost("business_name");
		$dba_name 	   			= $this->getRequest()->getPost("dba_name");
		$busines_type  			= $this->getRequest()->getPost("busines_type");
		$time_in_business   	= $this->getRequest()->getPost("time_in_business");
		$business_time   		= $this->getRequest()->getPost("business_time");
		$busines_credit   		= $this->getRequest()->getPost("busines_credit");
		$business_address   	= $this->getRequest()->getPost("business_address");
		$city   				= $this->getRequest()->getPost("city");
		$state   				= $this->getRequest()->getPost("state");
		$zip_code   			= $this->getRequest()->getPost("zip_code");
		$phone   				= $this->getRequest()->getPost("phone1")."-".$this->getRequest()->getPost("phone2")."-".$this->getRequest()->getPost("phone3");
		$fax   					= $this->getRequest()->getPost("fax1")."-".$this->getRequest()->getPost("fax2")."-".$this->getRequest()->getPost("fax3");
		$federal_tax_id   		= $this->getRequest()->getPost("federal_tax_id");
		$equipment_cost   		= $this->getRequest()->getPost("equipment_cost");
		$website   				= $this->getRequest()->getPost("website");
		$comments   			= $this->getRequest()->getPost("comments");
		$equipment_state   		= $this->getRequest()->getPost("equipment_state");
		$ship_address_option	= $this->getRequest()->getPost("ship_address_option");
		$ship_to_other_address	= $this->getRequest()->getPost("ship_to_other_address");
		$city3					= $this->getRequest()->getPost("city3");
		$state3					= $this->getRequest()->getPost("state3");
		$zip3					= $this->getRequest()->getPost("zip3");
		$location_option		= $this->getRequest()->getPost("location_option");
		$other_location_address	= $this->getRequest()->getPost("other_location_address");
		$city4					= $this->getRequest()->getPost("city4");
		$state4					= $this->getRequest()->getPost("state4");
		$zip_code4				= $this->getRequest()->getPost("city4");
		$owner_ship				= $this->getRequest()->getPost("owner_ship");
		$equipment_desc			= $this->getRequest()->getPost("equipment_desc");
		$first_name				= $this->getRequest()->getPost("first_name");
		$last_name				= $this->getRequest()->getPost("last_name");
		$title					= $this->getRequest()->getPost("title");
		$social_security		= $this->getRequest()->getPost("social1")."-".$this->getRequest()->getPost("social2")."-".$this->getRequest()->getPost("social3");
		$address				= $this->getRequest()->getPost("address");
		$city2					= $this->getRequest()->getPost("city2");
		$state2					= $this->getRequest()->getPost("state2");
		$zip2					= $this->getRequest()->getPost("zip2");
		$home_phone				= $this->getRequest()->getPost("home_phone1")."-".$this->getRequest()->getPost("home_phone2")."-".$this->getRequest()->getPost("home_phone3");
		$mobile_phone			= $this->getRequest()->getPost("mobile_phone1")."-".$this->getRequest()->getPost("mobile_phone2")."-".$this->getRequest()->getPost("mobile_phone3");
		$email					= $this->getRequest()->getPost("email");
		
		$template = 
		'<html>
		<table cellpadding="7">
			<tr> 
				<td>Sales Contact</td> <td>'.$sales_contact.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>Business Name</td> <td>'.$business_name.'</td>
			</tr>

			<tr> 
				<td>DBA Name</td> <td>'.$dba_name.'</td>
			</tr>

			<tr style="background-color:#eee"> 
				<td>Business Type</td> <td>'.$busines_type.'</td>
			</tr>

			<tr> 
				<td>Time In Business</td> <td>'.$time_in_business.' '.$business_time.' </td>
			</tr>

			<tr style="background-color:#eee"> 
				<td>Business Credit</td> <td>'.$busines_credit.'</td>
			</tr>

			<tr> 
				<td>Business Address</td> <td>'.$business_address.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>City</td> <td>'.$city.'</td>
			</tr>
			<tr> 
				<td>State</td> <td>'.$state.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>Zip Code</td> <td>'.$zip_code.'</td>
			</tr>
			<tr> 
				<td>Business Phone</td> <td>'.$phone.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>Fax</td> <td>'.$fax.'</td>
			</tr>
			<tr> 
				<td>Federal Tax ID</td> <td>'.$federal_tax_id.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>Web Site</td> <td>'.$website.'</td>
			</tr>
			<tr> 
				<td>Comments</td> <td>'.$comments.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>Equipment Cost</td> <td>'.$equipment_cost.' '.$equipment_state.'</td>
			</tr>
			<tr> 
				<td>Ship To Address</td> <td>'.$ship_address_option.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>Ship To Other Address</td> <td>'.$ship_to_other_address.'</td>
			</tr>
			<tr> 
				<td>City</td> <td>'.$city3.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>State</td> <td>'.$state3.'</td>
			</tr>
			<tr> 
				<td>Zip Code</td> <td>'.$zip3.'</td>
			</tr>

			<tr style="background-color:#eee"> 
				<td>Equipment Location</td> <td>'.$location_option.'</td>
			</tr>

			<tr> 
				<td>Other Equipment Location</td> <td>'.$other_location_address.'</td>
			</tr>

			<tr style="background-color:#eee"> 
				<td>City</td> <td>'.$city4.'</td>
			</tr>
			<tr> 
				<td>State</td> <td>'.$state4.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>Zip Code</td> <td>'.$zip_code4.'</td>
			</tr>
			<tr> 
				<td>Equpment Description</td> <td>'.$equipment_desc.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>First Name</td> <td>'.$first_name.'</td>
			</tr>
			<tr> 
				<td>Last Name</td> <td>'.$last_name.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>Title</td> <td>'.$title.'</td>
			</tr>
			<tr> 
				<td>Owner Ship</td> <td>'.$owner_ship.'</td>
			</tr>

			<tr style="background-color:#eee"> 
				<td>Social Security</td> <td>'.$social_security.'</td>
			</tr>

			<tr> 
				<td>Address</td> <td>'.$address.'</td>
			</tr>

			<tr style="background-color:#eee"> 
				<td>City</td> <td>'.$city2.'</td>
			</tr>
			<tr> 
				<td>State</td> <td>'.$state2.'</td>
			</tr>
			<tr style="background-color:#eee"> 
				<td>Zip Code</td> <td>'.$zip2.'</td>
			</tr>
			<tr> 
				<td>Home Phone</td> <td>'.$home_phone.'</td>
			</tr>

			<tr style="background-color:#eee"> 
				<td>Mobile Phone</td> <td>'.$mobile_phone.'</td>
			</tr>

			<tr> 
				<td>Email</td> <td>'.$email.'</td>
			</tr>		

		</table>
		</html>';
		
		$sender=Mage::getStoreConfig('trans_email/ident_general/email');
		$sendername=Mage::getStoreConfig('trans_email/ident_general/name');
		$email=$sales_contact;//'cafer@chefsdeal.com';
		
		$mail = Mage::getModel('core/email');
		$mail->setToName($email);
		$mail->setToEmail($email);
		$mail->setBody($template);
		$mail->setSubject('Chefs Deal Restaurant Equipment Financing Request');
		$mail->setFromEmail($sender);
		$mail->setFromName($sendername);
		$mail->setType('html');// YOu can use Html or text as Mail format

		try {
			$mail->send();
			Mage::getSingleton('core/session')->addSuccess($this->__('Your financing request is placed. Financing team will contact you as soon as possible !'));
		}
		catch (Exception $e) {
			Mage::getSingleton('core/session')->addError('Unable to send your request !');
		}
		
        $this->_redirect('/home');//will redirect to form page
    }
}