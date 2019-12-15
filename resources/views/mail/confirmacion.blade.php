<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
</head>

<body style="background: red ">
    <table width="100%" height="100%">
        <tr>
            <td style="background-color: #ecf0f1">
                <div style="width: 100%;margin:20px 0; display: inline-block;text-align: center">
                    <img style="padding: 0; width: 200px; margin: 5px" src="https://s19.postimg.org/np3e1b7pv/premier.jpg">
                    <img style="padding: 0; width: 200px; margin: 5px" src="https://s19.postimg.org/ejzml6toz/banner_hoenn.png">
                </div>
                <div style="color: #34495e; margin: 4% 10% 2%; text-align: justify;font-family: sans-serif">
                    <h2 style="color: #e67e22; margin: 0 0 7px">Hola {{$datos_formulario['nombre']}}!</h2>
                    <p style="margin: 2px; font-size: 15px">
                        Somos la comunidad Poketrainers Trujillo, una comunidad de Pokémon VGC que se encuentra en la ciudad de Trujillo Perú.<br>
                        Estando próxima la salida de Pokémon Sol y Luna en la comunidad estamos realizando una serie de actividades que nos preparara para su llegada, así que los invitamos a formar parte de la comunidad y a acompañarnos en esta nueva aventura en la región de Alola, donde muchos pokemon y aventuras nos esperan!<br>
                        Entre las actividades tenemos:</p>
                    <ul style="font-size: 15px;  margin: 10px 0">
                        <li>Batallas amistosas.</li>
                        <li>Torneos Oficiales.</li>
                        <li>Intercambios de Pokémon.</li>
                        <li>Actividades de integración.</li>
                        <li>Muchas sorpresas más.</li>
                    </ul>
                    <div style="width: 100%; text-align: center">
                        <a style="text-decoration: none; border-radius: 5px; padding: 11px 23px; color: white; background-color: #3498db" href="{{ url('/registro/verificacion/'.$datos_formulario['cod_confirmacion'])}}">Verificar correo</a>
                    </div>
                    <p style="color: #b3b3b3; font-size: 12px; text-align: center;margin: 30px 0 0">Poketrainers Trujillo 2016</p>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>