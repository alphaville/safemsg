<?php
class RegisterMessageResponse implements JsonSerializable
{

    private $id;        # Identifier
    private $status;    # Status 

    public function __construct($id, $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    public function jsonSerialize()
    {
        return [
            'response' => [
                'id' => $this->id,
                'created' => time(),
                'status' => $this->status
            ]
        ];
    }
}


class RetrievedMessageResponse implements JsonSerializable
{

    private $msg;
    private $status;

    public function __construct($msg, $status)
    {
        $this->msg = $msg;
        $this->status = $status;
    }

    public function jsonSerialize()
    {
        return [
            'response' => [
                'msg' => $this->msg,
                'status' => $this->status
            ]
        ];
    }
}

?>