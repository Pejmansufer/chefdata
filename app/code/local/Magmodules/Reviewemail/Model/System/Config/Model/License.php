<?php
/**
 * Magmodules.eu - http://www.magmodules.eu - info@magmodules.eu
 * =============================================================
 * NOTICE OF LICENSE [Single domain license]
 * This source file is subject to the EULA that is
 * available through the world-wide-web at:
 * http://www.magmodules.eu/license-agreement/
 * =============================================================
 * @category    Magmodules
 * @package     Magmodules_Reviewemail
 * @author      Magmodules <info@magmodules.eu>
 * @copyright   Copyright (c) 2015 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/  
 * =============================================================
 */
 
class Magmodules_Reviewemail_Model_System_Config_Model_License extends Mage_Core_Model_Config_Data {

    public function afterLoad() {
       $data = call_user_func(str_rot13('onfr64_qrpbqr'), "CgkJJG1vZHVsZSA9ICdNYWdtb2R1bGVzX1Jldmlld2VtYWlsJzsKCQkkbW9kdWxlX3ZlcnNpb24gPSAnTWFnbW9kdWxlX1JldjE5MTI4Myc7CgkJJG1vZHVsZV9wYXRoID0gJ3Jldmlld2VtYWlsL2dlbmVyYWwvJzsgCgkJJG1vZHVsZV9zZXJ2ZXIgPSBzdHJfcmVwbGFjZSgnd3d3LicsICcnLCAkX1NFUlZFUlsnSFRUUF9IT1NUJ10pOyAKCQkkbW9kdWxlX2luc3RhbGxlZCA9IE1hZ2U6OmdldENvbmZpZygpLT5nZXROb2RlKCktPm1vZHVsZXMtPk1hZ21vZHVsZXNfUmV2aWV3ZW1haWwtPnZlcnNpb247CgkJcmV0dXJuIGJhc2U2NF9lbmNvZGUoYmFzZTY0X2VuY29kZShiYXNlNjRfZW5jb2RlKCRtb2R1bGUgLiAnOycgLiAkbW9kdWxlX3ZlcnNpb24gLiAnOycgLiAkbW9kdWxlX2luc3RhbGxlZCAuICc7JyAuIHRyaW0oTWFnZTo6Z2V0TW9kZWwoJ2NvcmUvY29uZmlnX2RhdGEnKS0+bG9hZCgkbW9kdWxlX3BhdGggLiAnbGljZW5zZV9rZXknLCAncGF0aCcpLT5nZXRWYWx1ZSgpKSAuICc7JyAuICRtb2R1bGVfc2VydmVyIC4gJzsnIC4gTWFnZTo6Z2V0VXJsKCkgLiAnOycgLiBNYWdlOjpnZXRTaW5nbGV0b24oJ2FkbWluL3Nlc3Npb24nKS0+Z2V0VXNlcigpLT5nZXRFbWFpbCgpIC4gJzsnIC4gTWFnZTo6Z2V0U2luZ2xldG9uKCdhZG1pbi9zZXNzaW9uJyktPmdldFVzZXIoKS0+Z2V0TmFtZSgpIC4gJzsnIC4gJF9TRVJWRVJbJ1NFUlZFUl9BRERSJ10pKSk7Cg==");
	   $this->setValue(eval($data));
    }

    static function isEnabled() {
        return eval(call_user_func(str_rot13('onfr64_qrpbqr'), "CQkkbW9kdWxlX3ZlcnNpb24gPSAnTWFnbW9kdWxlX1JldjE5MTI4Myc7CgkJJG1vZHVsZV9wYXRoID0gJ3Jldmlld2VtYWlsL2dlbmVyYWwvJzsgCgkJJG1vZHVsZV9zZXJ2ZXIgPSBzdHJfcmVwbGFjZSgnd3d3LicsICcnLCAkX1NFUlZFUlsnSFRUUF9IT1NUJ10pOwoJCQoJCWlmKChzaGExKHNoYTEoJG1vZHVsZV92ZXJzaW9uIC4gJ19tYWdfJyAuICRtb2R1bGVfc2VydmVyKSkpICE9ICh0cmltKE1hZ2U6OmdldE1vZGVsKCdjb3JlL2NvbmZpZ19kYXRhJyktPmxvYWQoJG1vZHVsZV9wYXRoIC4gJ2xpY2Vuc2Vfa2V5JywgJ3BhdGgnKS0+Z2V0VmFsdWUoKSkpKSB7CgkJCU1hZ2U6OmdldENvbmZpZygpLT5zYXZlQ29uZmlnKCRtb2R1bGVfcGF0aCAuICdlbmFibGVkJywgMCk7IAoJCQlNYWdlOjpnZXRDb25maWcoKS0+Y2xlYW5DYWNoZSgpOyAKCQkJTWFnZTo6Z2V0U2luZ2xldG9uKCdhZG1pbmh0bWwvc2Vzc2lvbicpLT5hZGRFcnJvcihNYWdlOjpoZWxwZXIoJ3Jldmlld2VtYWlsJyktPl9fKCJUaGUgUmV2aWV3IGVtYWlsIGV4dGVuc2lvbiBjb3VsZG4ndCBiZSBlbmFibGVkLiBQbGVhc2UgbWFrZSBzdXJlIHlvdSBhcmUgdXNpbmcgYSB2YWxpZCBsaWNlbnNlIGtleS4iKSk7IAoJCQlyZXR1cm4gZmFsc2U7IAoJCX0gZWxzZSB7IAoJCQlyZXR1cm4gdHJ1ZTsgCgkJfQ=="));
    }
        
}