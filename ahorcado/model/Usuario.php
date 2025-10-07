<?php
 class Usuario{
    
    public function __construct(
        public int $codigo,
        public String $nombre,
        public String $usuario,
        public String $constrasenia,
        public array $partidas=[]
    ){}
    public function saveData(Usuario $user){
        $file="usuarios";
        $list=$user->getData();
        $encontrado=false;
        foreach ($list as $key => $value) {
            if($user->equals($value)){
                $value=$user;
                $encontrado=true;
            }
        }
        if(!$encontrado){
            $list[]=$user;
        }
        $file=fopen($file,"w");
        foreach ($list as $key => $value) {
            $codigo=$value->codigo;
            $nombre=$value->nombre;
            $usuario=$value->usuario;
            $contrasenia=$value->constrasenia;
        }
    }

    public function getPartidas(int $codigo){
        $file="partidas";
        $content=explode("\n",file_get_contents($file));
        $listPartidas=[];
        foreach ($content as $key => $value) {
            $partidaArray=explode(" ",$value);
            if($partidaArray[0]=="$codigo"){
                $partida=new Partida($partidaArray[1],$partidaArray[2]);
                $listPartidas[]=$partida;
            }
        }
        return $listPartidas;
    }

    public function getAllPartidas(){
        $file="partidas";
        $content=explode("\n",file_get_contents($file));
        $listPartidas=[];
        foreach ($content as $key => $value) {
            $partidaArray=explode(" ",$value);
                $partida=new Partida($partidaArray[1],$partidaArray[2]);
                $listPartidas[]=$partida;
        }
        return $listPartidas;
    }
    public function getData(){
        $file="usuarios";
        $content=explode("\n",file_get_contents($file));
        $listUsers=[];
        foreach ($content as $key => $value) {
            $userArray=explode(" ",$value);
            $codigo=(int)$userArray[0];
            $nombre=$userArray[1];
            $usario=$userArray[2];
            $contrasenia=$userArray[3];
            $user=new Usuario($codigo,$nombre,$usario,$contrasenia,[]);
            $partidas=$user->getPartidas($codigo);
            $user=new Usuario($codigo,$nombre,$usario,$contrasenia,$partidas);
        }
        return $listUsers;
    }

    function equals(Usuario $otrousuario){
        return $this->codigo===$otrousuario->codigo;
    }
}
?>