<?php
/*********************************************************************************
Copyright (c) 2010, OneLogin, Inc.
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of the <organization> nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL ONELOGIN, INC. BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 ********************************************************************************/
 
  require(dirname(__FILE__) . '/../../xmlseclibs/xmlseclibs.php');

  /**
   * Determine if the SAML response is valid using a provided x509 certificate.
   */
  class SamlXmlSec {
    /**
     * A SamlResponse class provided to the constructor.
     */
    private $settings;

    /**
     * The documentument to be tested.
     */
    private $document;

    /**
     * Construct the SamlXmlSec object.
     *
     * @param SamlResponse $settings
     *   A SamlResponse settings object containing the necessary
     *   x509 certicate to test the document.
     * @param string $document
     *   The document to test.
     */
    function __construct($settings, $document) {
      $this->settings = $settings;
      $this->document = $document;
    }


    /**
     * Determine if the document passes the security test.
     *
     * @return
     *   TRUE if the document passes. This could throw a generic Exception
     *   if the document or key cannot be found.
     */
    
    function validateNumAssertions(){
      $rootNode = $this->document; //->documentElement->ownerDocument;
      $assertionNodes = $rootNode->getElementsByTagName('Assertion');
      return ($assertionNodes->length == 1);
    }

    function validateTimestamps(){
      $rootNode = $this->document;
      $timestampNodes = $rootNode->getElementsByTagName('Conditions');
      for($i=0;$i<$timestampNodes->length;$i++){
        $nbAttribute = $timestampNodes->item($i)->attributes->getNamedItem("NotBefore");
        $naAttribute = $timestampNodes->item($i)->attributes->getNamedItem("NotOnOrAfter");
        if($nbAttribute && strtotime($nbAttribute->textContent) > time()){
            return false;
        }
        if($naAttribute && strtotime($naAttribute->textContent) <= time()){
            return false;
        }
      }
      return true;
    }
 
    function is_valid() {
    	$objXMLSecDSig = new XMLSecurityDSig();

    	$objDSig = $objXMLSecDSig->locateSignature($this->document);
    	if (! $objDSig) {
    		throw new Exception("Cannot locate Signature Node");
    	}
    	$objXMLSecDSig->canonicalizeSignedInfo();
    	$objXMLSecDSig->idKeys = ['ID'];

    	$retVal = $objXMLSecDSig->validateReference();
    	if (! $retVal) {
    		throw new Exception("Reference Validation Failed");
    	}

    	$objKey = $objXMLSecDSig->locateKey();
    	if (! $objKey ) {
    		throw new Exception("We have no idea about the key");
    	}
    	$key = NULL;

    	$singleAssertion = $this->validateNumAssertions();
      if (!$singleAssertion){
        throw new Exception("Only one SAMLAssertion allowed");
      }

      $validTimestamps = $this->validateTimestamps();
      if (!$validTimestamps){
        throw new Exception("SAMLAssertion conditions not met");
      }

    	$objKeyInfo = XMLSecEnc::staticLocateKeyInfo($objKey, $objDSig);

      $objKey->loadKey($this->settings->x509certificate, FALSE, true);

    	$result = $objXMLSecDSig->verify($objKey);
    	return $result;
    }

 }

?>
