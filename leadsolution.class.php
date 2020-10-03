<?php

class Leadsolution
{
    static public function handle($endpoint = 'http://leadtowin.afiliasolution.com/leads')
    {
        if (isset($_GET['source'])) {
            setcookie('source', $_GET['source'], time() + 60 * 60 * 24 * 30 /* 30 days */);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            return self::submit($_POST, $endpoint);
        }

        return new LeadsolutionResponse();
    }

    static public function submit(array $data, $endpoint)
    {
        $data['format'] = 'json';

        $ch = curl_init();

        $opts = array(
            CURLOPT_URL            => $endpoint,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_REFERER        => $_SERVER['HTTP_REFERER']
        );


        curl_setopt_array($ch, $opts);
        $response = curl_exec($ch);
        $response = json_decode($response);
        curl_close($ch);
        $chTwo= curl_init();
        // values API 2

        $dataApiTwo = array(
          'first_name'      => $_POST['firstname'],
          'last_name'       => $_POST['lastname'],
          'email'           => $_POST['email'],
          'company'         => $_POST['meta_empresa'],
          'Zip'             => $_POST['meta_Zip'],
          '00N30000000wQso' => $_POST['meta_cantidadVehiculos'],
          // 'Zip'             => $_POST['meta_codigopostal'],
          'phone'           => $_POST['mobile_phone'],
          'lead_source'     => 'Advanza',
          'elqFormName'     => 'FTM-W2L-form',
          'elqSiteId'       => '519780151',
          'oid'             => '00D3000000013lQ',
          'country'         => 'ESP',
          'marketing_code'  => 'LeadToWin',
          'gdpr_demo_request'  => '1',
          'gdpr_consent_obtained'  => '1',
          'utm_source'      => 'adwords'
        );

        $optsTwo = array(
            CURLOPT_URL            => 'https://telogis-referee-primary.herokuapp.com/form-handler',
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $dataApiTwo,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_REFERER        => $_SERVER['HTTP_REFERER']
        );

        curl_setopt_array($chTwo, $optsTwo);
        $response2 = curl_exec($chTwo);
        $response2 = json_decode($response2);
        curl_close($chTwo);

        //echo '<pre>' . var_export($response, true) . '</pre>';
        //var_dump($response2);exit;

        header('Location: http://3.17.232.223/LET/Comparador%202.0/thankYou.php?id='.$response->lead->id.'&api2='.$response2);

    }

    static public function srcInput($default)
    {
        return '<input type="hidden" name="source" value="'.self::getSource($default).'">';
    }

    static public function getSource($default)
    {
        return (isset($_GET['source']) ? $_GET['source'] : (isset($_COOKIE['source']) ? $_COOKIE['source'] : $default));
    }

    static public function ipInput()
    {
        return '<input type="hidden" name="ip" value="'.self::getIp().'">';
    }

    static public function getIp()
    {
        $keys = array('X_FORWARDED_FOR', 'HTTP_X_FORWARDED_FOR', 'CLIENT_IP', 'REMOTE_ADDR');

        foreach ($keys as $key) {
            if (isset($_SERVER[$key])) {
                return $_SERVER[$key];
            }
        }

        return $_SERVER['REMOTE_ADDR'];
    }
}

class LeadsolutionResponse
{
    private $response;

    public function __construct($response = null)
    {
        $this->response = $response;
    }

    public function isDuplicated()
    {
        if (!$this->isOk()) {
            return false;
        }

        return $this->response->lead->status == 'duplicated';
    }

    public function isOk()
    {
        return $this->exists() && $this->response->lead;
    }

    public function exists()
    {
        return is_object($this->response);
    }

    public function log()
    {
        if (empty($this->response)) {
            return $this->consoleLog('Empty response');
        }

        $response = json_encode($this->response);

        if (empty($response)) {
            return $this->consoleLog(json_last_error_msg());
        }

        return $this->consoleLog($response);
    }

    public function pixels()
    {
        if (!$this->isPixable() || empty($this->response->pixels)) {
            return false;
        }

        foreach ($this->response->pixels as $pixel) {
            echo $pixel;
        }
    }

    private function isPixable()
    {
        if (!$this->isOk()) {
            return false;
        }

        if (in_array($this->response->lead->status, array('duplicated', 'discarded', 'soft_discard'))) {
            return false;
        }

        return true;
    }

    public function tags()
    {
        if (!$this->isPixable() || empty($this->response->tags)) {
            return;
        }

        foreach ($this->response->tags as $tag) {
            echo $tag;
        }
    }

    public function getResponse()
    {
        return $this->response;
    }

    private function consoleLog($str)
    {
        echo sprintf("<script>console.log('%s')</script>", $str);
    }

}
