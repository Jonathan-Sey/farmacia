<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipio;
use App\Models\Departamento;

class MunicipiosSeeder extends Seeder
{
    public function run()
    {
        $departamentos_municipios = [
            'Alta Verapaz' => [
                'Cobán', 'Santa Cruz Verapaz', 'San Cristóbal Verapaz', 'Tactic',
                'Tamahú', 'Tucurú', 'Panzós', 'Senahú', 'San Pedro Carchá',
                'Santa Catalina La Tinta', 'Chahal', 'Chisec', 'Raxruhá', 'Fray Bartolomé de las Casas',
                'Santa María Cahabón', 'Lanquin'
            ],
            'Baja Verapaz' => [
                'Salamá', 'San Miguel Chicaj', 'Rabinal', 'Cubulco',
                'Granados', 'Santa Cruz El Chol', 'Purulhá', 'San Jerónimo'
            ],
            'Chimaltenango' => [
                'Chimaltenango', 'San José Poaquil', 'San Martín Jilotepeque',
                'Comalapa', 'Santa Apolonia', 'Tecpán Guatemala',
                'Patzún', 'Pochuta', 'Patzicía', 'Santa Cruz Balanyá',
                'Acatenango', 'Yepocapa', 'Parramos', 'Zaragoza', 'El Tejar'
            ],
            'Chiquimula' => [
                'Chiquimula', 'San José La Arada', 'San Juan Ermita',
                'Jocotán', 'Camotán', 'Olopa', 'Esquipulas', 'Concepción Las Minas',
                'Quetzaltepeque'
            ],
            'El Progreso' => [
                'Guastatoya', 'Morazán', 'San Agustín Acasaguastlán',
                'San Cristóbal Acasaguastlán', 'El Jícaro',
                'Sansare', 'Sanarate', 'San Antonio La Paz'
            ],
            'Escuintla' => [
                'Escuintla', 'Santa Lucía Cotzumalguapa', 'La Democracia',
                'Siquinalá', 'Masagua', 'Tiquisate', 'La Gomera',
                'Guanagazapa', 'San José', 'Iztapa', 'Palín', 'Nueva Concepción'
            ],
            'Guatemala' => [
                'Guatemala', 'Santa Catarina Pinula', 'San José Pinula',
                'San José del Golfo', 'Palencia', 'Chinautla',
                'San Pedro Ayampuc', 'Mixco', 'San Pedro Sacatepéquez',
                'San Juan Sacatepéquez', 'San Raymundo', 'Chuarrancho',
                'Fraijanes', 'Amatitlán', 'Villa Nueva', 'Villa Canales',
                'Petapa'
            ],
            'Huehuetenango' => [
                'Huehuetenango', 'Chiantla', 'Malacatancito', 'Cuilco',
                'Nentón', 'San Pedro Necta', 'Jacaltenango',
                'Soloma', 'Ixtahuacán', 'Santa Bárbara',
                'La Libertad', 'La Democracia', 'San Miguel Acatán',
                'San Rafael La Independencia', 'San Sebastián Coatán',
                'Santa Eulalia', 'San Mateo Ixtatán', 'Colotenango',
                'San Juan Atitán', 'Santa Cruz Barillas', 'Aguacatán',
                'San Rafael Petzal', 'San Gaspar Ixchil', 'Santiago Chimaltenango',
                'Santa Ana Huista', 'Unión Cantinil'
            ],
            'Izabal' => [
                'Puerto Barrios', 'Livingston', 'El Estor',
                'Morales', 'Los Amates'
            ],
            'Jalapa' => [
                'Jalapa', 'San Pedro Pinula', 'San Luis Jilotepeque',
                'San Manuel Chaparrón', 'San Carlos Alzatate',
                'Monjas', 'Mataquescuintla'
            ],
            'Jutiapa' => [
                'Jutiapa', 'El Progreso', 'Santa Catarina Mita',
                'Agua Blanca', 'Asunción Mita', 'Yupiltepeque',
                'Atescatempa', 'Jerez', 'El Adelanto', 'Zapotitlán',
                'Comapa', 'Jalpatagua', 'Conguaco', 'Moyuta',
                'Pasaco', 'Quesada'
            ],
            'Petén' => [
                'Flores', 'San Benito', 'San Andrés', 'La Libertad',
                'San Francisco', 'Santa Ana', 'Dolores', 'San Luis',
                'Sayaxché', 'Melchor de Mencos', 'Poptún'
            ],
            'Quetzaltenango' => [
                'Quetzaltenango', 'Salcajá', 'Olintepeque', 'San Carlos Sija',
                'Sibilia', 'Cabricán', 'Cajolá', 'San Miguel Sigüilá',
                'San Juan Ostuncalco', 'San Mateo', 'Concepción Chiquirichapa',
                'San Martín Sacatepéquez', 'Almolonga', 'Cantel',
                'Huitán', 'Zunil', 'Colomba', 'San Francisco La Unión',
                'El Palmar', 'Coatepeque', 'Génova', 'Flores Costa Cuca',
                'La Esperanza'
            ],
            'Quiché' => [
                'Santa Cruz del Quiché', 'Chiché', 'Chinique', 'Zacualpa',
                'Chajul', 'Santo Tomás Chichicastenango', 'Patzité',
                'San Antonio Ilotenango', 'San Pedro Jocopilas',
                'Cunén', 'Uspantán', 'Sacapulas', 'San Bartolomé Jocotenango',
                'Canillá', 'San Andrés Sajcabajá', 'San Juan Cotzal',
                'Joyabaj', 'Nebaj'
            ],
            'Retalhuleu' => [
                'Retalhuleu', 'San Sebastián', 'Santa Cruz Muluá',
                'San Martín Zapotitlán', 'San Felipe', 'San Andrés Villa Seca',
                'Champerico', 'Nuevo San Carlos', 'El Asintal'
            ],
            'Sacatepéquez' => [
                'Antigua Guatemala', 'Jocotenango', 'Pastores',
                'Sumpango', 'Santo Domingo Xenacoj', 'Santiago Sacatepéquez',
                'San Bartolomé Milpas Altas', 'San Lucas Sacatepéquez',
                'Santa Lucía Milpas Altas', 'Magdalena Milpas Altas',
                'Santa María de Jesús', 'Ciudad Vieja', 'San Miguel Dueñas',
                'San Juan Alotenango', 'San Antonio Aguas Calientes'
            ],
            'San Marcos' => [
                'San Marcos', 'San Pedro Sacatepéquez', 'San Antonio Sacatepéquez',
                'Comitancillo', 'San Miguel Ixtahuacán', 'Concepción Tutuapa',
                'Tacaná', 'Sibinal', 'Tajumulco', 'Tejutla', 'San Rafael Pie de la Cuesta',
                'Nuevo Progreso', 'El Tumbador', 'San José El Rodeo',
                'Malacatán', 'Catarina', 'Ayutla', 'Ocós',
                'San Pablo', 'El Quetzal', 'La Reforma', 'Pajapita',
                'Ixchiguán', 'San Cristóbal Cucho', 'Esquipulas Palo Gordo',
                'Río Blanco', 'San Lorenzo'
            ],
            'Santa Rosa' => [
                'Cuilapa', 'Barberena', 'Santa Rosa de Lima',
                'Casillas', 'San Rafael Las Flores', 'Oratorio',
                'San Juan Tecuaco', 'Chiquimulilla', 'Taxisco',
                'Santa María Ixhuatán', 'Guazacapán', 'Pueblo Nuevo Viñas',
                'Nueva Santa Rosa'
            ],
            'Sololá' => [
                'Sololá', 'San José Chacayá', 'Santa María Visitación',
                'Santa Lucía Utatlán', 'Nahualá', 'Santa Catarina Ixtahuacán',
                'Santa Clara La Laguna', 'Concepción', 'San Andrés Semetabaj',
                'Panajachel', 'Santa Catarina Palopó', 'San Antonio Palopó',
                'San Lucas Tolimán', 'Santa Cruz La Laguna',
                'San Pablo La Laguna', 'San Marcos La Laguna',
                'San Juan La Laguna', 'San Pedro La Laguna',
                'Santiago Atitlán'
            ],
            'Suchitepéquez' => [
                'Mazatenango', 'Cuyotenango', 'San Francisco Zapotitlán',
                'San Bernardino', 'San José El Idolo', 'Santo Domingo Suchitepéquez',
                'San Lorenzo', 'Samayac', 'San Pablo Jocopilas',
                'San Antonio Suchitepéquez', 'San Miguel Panán',
                'San Gabriel', 'Chicacao', 'Patulul', 'Santa Bárbara',
                'Santo Tomás La Unión'
            ],
            'Totonicapán' => [
                'Totonicapán', 'San Cristóbal Totonicapán',
                'San Francisco El Alto', 'San Andrés Xecul',
                'Momostenango', 'Santa María Chiquimula',
                'Santa Lucía La Reforma', 'San Bartolo'
            ],
            'Zacapa' => [
                'Zacapa', 'Estanzuela', 'Río Hondo', 'Gualán',
                'Teculután', 'Usumatlán', 'Cabañas',
                'La Unión', 'Huité'
            ],
        ];

        foreach ($departamentos_municipios as $departamento_nombre => $municipios) {
            $departamento = Departamento::where('nombre', $departamento_nombre)->first();

            foreach ($municipios as $nombre_municipio) {
                Municipio::create([
                    'nombre' => $nombre_municipio,
                    'departamento_id' => $departamento->id,
                ]);
            }
        }
    }
}
