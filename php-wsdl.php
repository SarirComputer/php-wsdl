<?php

$data = [
    'name' => 'MyWSDL',
    'url' => 'http://127.0.0.1',
    'path' => '/webservice/index.php?wsdl',
    'functions' => [
        'request' => [
            'api' => 'string',
            'params' => 'string',
        ],
        'verify' => [
            'api' => 'string',
            'params' => 'string',
        ],
    ],
];

ob_start();

?>
<?xml version="1.0" encoding="UTF-8"?>
<definitions xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tns="<?=$data['url']?>/soap/<?=$data['name']?>" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" targetNamespace="<?=$data['url']?>/soap/<?=$data['name']?>">
    <types>
        <xsd:schema targetNamespace="<?=$data['url']?>/soap/<?=$data['name']?>">
        <xsd:import namespace="http://schemas.xmlsoap.org/soap/encoding/" />
        <xsd:import namespace="http://schemas.xmlsoap.org/wsdl/" />
        </xsd:schema>
    </types>
    <?php foreach ($data['functions'] as $key => $value): ?>
    <message name="<?=$key?>Request">
        <?php foreach ($value as $k => $v): ?>
        <part name="<?=$k?>" type="xsd:<?=$v?>" />
        <?php endforeach;?>
    </message>
    <message name="<?=$key?>Response">
        <part name="return" type="xsd:string" />
    </message>
    <?php endforeach;?>
    <portType name="<?=$data['name']?>PortType">
        <?php foreach ($data['functions'] as $key => $value): ?>
        <operation name="<?=$key?>">
            <input message="tns:<?=$key?>Request" />
            <output message="tns:<?=$key?>Response" />
        </operation>
        <?php endforeach;?>
    </portType>
    <binding name="<?=$data['name']?>Binding" type="tns:<?=$data['name']?>PortType">
        <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http" />
        <?php foreach ($data['functions'] as $key => $value): ?>
        <operation name="<?=$key?>">
            <soap:operation soapAction="<?=$data['url']?><?=$data['path']?>/<?=$key?>" style="rpc" />
            <input>
                <soap:body use="encoded" namespace="http://soapinterop.org" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" />
            </input>
            <output>
                <soap:body use="encoded" namespace="http://soapinterop.org" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" />
            </output>
        </operation>
        <?php endforeach;?>
    </binding>
    <service name="<?=$data['name']?>">
        <port name="<?=$data['name']?>Port" binding="tns:<?=$data['name']?>Binding">
            <soap:address location="<?=$data['url']?><?=$data['path']?>" />
        </port>
    </service>
</definitions>
<?php

$result = ob_get_contents();

ob_end_clean();

print '<pre>';
print htmlspecialchars($result);

?>