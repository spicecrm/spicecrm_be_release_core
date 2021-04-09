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
 
  require 'xmlsec.php';

  /**
   * Parse the SAML response and maintain the XML for it.
   */
  class SamlResponse {
    /**
     * A SamlResponse class provided to the constructor.
     */
    private $settings;

    /**
     * The decoded, unprocessed XML assertion provided to the constructor.
     */
    public $assertion;

    /**
     * A DOMDocument class loaded from the $assertion.
     */
    public $xml;

    // At this time these private members are unused.
    private $nameid;
    private $xpath;

    /**
     * Construct the response object.
     *
     * @param SamlResponse $settings
     *   A SamlResponse settings object containing the necessary
     *   x509 certicate to decode the XML.
     * @param string $assertion
     *   A UUEncoded SAML assertion from the IdP.
     */
    function __construct($settings, $assertion) {
      $this->settings = $settings;
      $this->assertion = base64_decode($assertion);
      $this->xml = new DOMDocument();
      $this->loadXML($this->xml, $this->assertion);
    }

    /**
     * Determine if the SAML Response is valid using the certificate.
     *
     * @return
     *   TRUE if the document passes. This could throw a generic Exception
     *   if the document or key cannot be found.
     */
    function is_valid() {
      $xmlsec = new SamlXmlSec($this->settings, $this->xml);
      return $xmlsec->is_valid();
    }

    /**
     * Get the NameID provided by the SAML response from the IdP.
     */
    function get_nameid() {
      $xpath = new DOMXPath($this->xml);
			$xpath->registerNamespace("samlp","urn:oasis:names:tc:SAML:2.0:protocol");
			$xpath->registerNamespace("saml","urn:oasis:names:tc:SAML:2.0:assertion");
      $query = "/samlp:Response/saml:Assertion/saml:Subject/saml:NameID";

      $entries = $xpath->query($query);
      return $entries->item(0)->nodeValue;
    }

    /**
     * This function load an XML string in a save way.
     * Prevent XEE/XXE Attacks
     *
     * @param DOMDocument $dom The document where load the xml.
     * @param string $xml The XML string to be loaded.
     *
     * @throws DOMExceptions
     *
     * @return DOMDocument $dom The result of load the XML at the DomDocument
     */
    public function loadXML($dom, $xml)
    {
        assert('$dom instanceof DOMDocument');
        assert('is_string($xml)');

        if (strpos($xml, '<!ENTITY') !== false) {
            throw new Exception('Detected use of ENTITY in XML, disabled to prevent XXE/XEE attacks');
        }

        $oldEntityLoader = libxml_disable_entity_loader(true);
        $res = $dom->loadXML($xml);
        libxml_disable_entity_loader($oldEntityLoader);

        if (!$res) {
            return false;
        } else {
            return $dom;
        }
    }
  }

?>
