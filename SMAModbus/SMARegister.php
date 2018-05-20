<?php

/**
 * Class SMARegister
 * Constants with sma register and value translations
 *
 * @version     0.1
 * @category    Symcon
 * @package     de.codeking.symcon
 * @author      Frank Herrmann <frank@codeking.de>
 * @link        https://codeking.de
 * @link        https://github.com/CodeKing/de.codeking.symcon
 *
 */
class SMARegister
{
    /**
     * device addresses
     * addresses with fixed values
     */
    const device_addresses = [
        'default' => [
            /**
             * Global
             */
            30051 => [
                'name' => 'Device class',
                'count' => 2,
                'type' => 'U32',
                'format' => 'ENUM',
                'mapping' => [
                    460 => 'Solar Inverter',
                    8000 => 'All Devices',
                    8001 => 'Solar Inverter',
                    8002 => 'Wind Turbine Inverter',
                    8007 => 'Battery Inverter',
                    8033 => 'Consumer',
                    8064 => 'Sensor System in General',
                    8065 => 'Electricity meter',
                    8128 => 'Communication device'
                ]
            ],
            30053 => [
                'name' => 'Device-ID',
                'count' => 2,
                'type' => 'U32',
                'format' => 'ENUM',
                'mapping' => [
                    9000 => 'SWR 700',
                    9001 => 'SWR 850',
                    9002 => 'SWR 850E',
                    9003 => 'SWR 1100',
                    9004 => 'SWR 1100E',
                    9005 => 'SWR 1100LV',
                    9006 => 'SWR 1500',
                    9007 => 'SWR 1600',
                    9008 => 'SWR 1700E',
                    9009 => 'SWR 1800U',
                    9010 => 'SWR 2000',
                    9011 => 'SWR 2400',
                    9012 => 'SWR 2500',
                    9013 => 'SWR 2500U',
                    9014 => 'SWR 3000',
                    9015 => 'SB 700',
                    9016 => 'SB 700U',
                    9017 => 'SB 1100',
                    9018 => 'SB 1100U',
                    9019 => 'SB 1100LV',
                    9020 => 'SB 1700',
                    9021 => 'SB 1900TLJ',
                    9022 => 'SB 2100TL',
                    9023 => 'SB 2500',
                    9024 => 'SB 2800',
                    9025 => 'SB 2800i',
                    9026 => 'SB 3000',
                    9027 => 'SB 3000US',
                    9028 => 'SB 3300',
                    9029 => 'SB 3300U',
                    9030 => 'SB 3300TL',
                    9031 => 'SB 3300TL HC',
                    9032 => 'SB 3800',
                    9033 => 'SB 3800U',
                    9034 => 'SB 4000US',
                    9035 => 'SB 4200TL',
                    9036 => 'SB 4200TL HC',
                    9037 => 'SB 5000TL',
                    9038 => 'SB 5000TLW',
                    9039 => 'SB 5000TL HC',
                    9040 => 'Convert 2700',
                    9041 => 'SMC 4600A',
                    9042 => 'SMC 5000',
                    9043 => 'SMC 5000A',
                    9044 => 'SB 5000US',
                    9045 => 'SMC 6000',
                    9046 => 'SMC 6000A',
                    9047 => 'SB 6000US',
                    9048 => 'SMC 6000UL',
                    9049 => 'SMC 6000TL',
                    9050 => 'SMC 6500A',
                    9051 => 'SMC 7000A',
                    9052 => 'SMC 7000HV',
                    9053 => 'SB 7000US',
                    9054 => 'SMC 7000TL',
                    9055 => 'SMC 8000TL',
                    9056 => 'SMC 9000TL-10',
                    9057 => 'SMC 10000TL-10',
                    9058 => 'SMC 11000TL-10',
                    9059 => 'SB 3000 K',
                    9060 => 'Unknown device',
                    9061 => 'SensorBox',
                    9062 => 'SMC 11000TLRP',
                    9063 => 'SMC 10000TLRP',
                    9064 => 'SMC 9000TLRP',
                    9065 => 'SMC 7000HVRP',
                    9066 => 'SB 1200',
                    9067 => 'STP 10000TL-10',
                    9068 => 'STP 12000TL-10',
                    9069 => 'STP 15000TL-10',
                    9070 => 'STP 17000TL-10',
                    9071 => 'SB 2000HF-30',
                    9072 => 'SB 2500HF-30',
                    9073 => 'SB 3000HF-30',
                    9074 => 'SB 3000TL-21',
                    9075 => 'SB 4000TL-21',
                    9076 => 'SB 5000TL-21',
                    9077 => 'SB 2000HFUS-30',
                    9078 => 'SB 2500HFUS-30',
                    9079 => 'SB 3000HFUS-30',
                    9080 => 'SB 8000TLUS',
                    9081 => 'SB 9000TLUS',
                    9082 => 'SB 10000TLUS',
                    9083 => 'SB 8000US',
                    9084 => 'WB 3600TL-20',
                    9085 => 'WB 5000TL-20',
                    9086 => 'SB 3800US-10',
                    9087 => 'Sunny Beam BT11',
                    9088 => 'Sunny Central 500CP',
                    9089 => 'Sunny Central 630CP',
                    9090 => 'Sunny Central 800CP',
                    9091 => 'Sunny Central 250U',
                    9092 => 'Sunny Central 500U',
                    9093 => 'Sunny Central 500HEUS',
                    9094 => 'Sunny Central 760CP',
                    9095 => 'Sunny Central 720CP',
                    9096 => 'Sunny Central 910CP',
                    9097 => 'SMU8',
                    9098 => 'STP 5000TL-20',
                    9099 => 'STP 6000TL-20',
                    9100 => 'STP 7000TL-20',
                    9101 => 'STP 8000TL-10',
                    9102 => 'STP 9000TL-20',
                    9103 => 'STP 8000TL-20',
                    9104 => 'SB 3000TL-JP-21',
                    9105 => 'SB 3500TL-JP-21',
                    9106 => 'SB 4000TL-JP-21',
                    9107 => 'SB 4500TL-JP-21',
                    9108 => 'SCSMC',
                    9109 => 'SB 1600TL-10',
                    9110 => 'SSM US',
                    9111 => 'SMA radio-controlled socket',
                    9112 => 'WB 2000HF-30',
                    9113 => 'WB 2500HF-30',
                    9114 => 'WB 3000HF-30',
                    9115 => 'WB 2000HFUS-30',
                    9116 => 'WB 2500HFUS-30',
                    9117 => 'WB 3000HFUS-30',
                    9118 => 'VIEW-10',
                    9119 => 'Sunny Home Manager',
                    9120 => 'SMID',
                    9121 => 'Sunny Central 800HE-20',
                    9122 => 'Sunny Central 630HE-20',
                    9123 => 'Sunny Central 500HE-20',
                    9124 => 'Sunny Central 720HE-20',
                    9125 => 'Sunny Central 760HE-20',
                    9126 => 'SMC 6000A-11',
                    9127 => 'SMC 5000A-11',
                    9128 => 'SMC 4600A-11',
                    9129 => 'SB 3800-11',
                    9130 => 'SB 3300-11',
                    9131 => 'STP 20000TL-10',
                    9132 => 'SMA CT Meter',
                    9133 => 'SB 2000HFUS-32',
                    9134 => 'SB 2500HFUS-32',
                    9135 => 'SB 3000HFUS-32',
                    9136 => 'WB 2000HFUS-32',
                    9137 => 'WB 2500HFUS-32',
                    9138 => 'WB 3000HFUS-32',
                    9139 => 'STP 20000TLHE-10',
                    9140 => 'STP 15000TLHE-10',
                    9141 => 'SB 3000US-12',
                    9142 => 'SB 3800US-12',
                    9143 => 'SB 4000US-12',
                    9144 => 'SB 5000US-12',
                    9145 => 'SB 6000US-12',
                    9146 => 'SB 7000US-12',
                    9147 => 'SB 8000US-12',
                    9148 => 'SB 8000TLUS-12',
                    9149 => 'SB 9000TLUS-12',
                    9150 => 'SB 10000TLUS-12',
                    9151 => 'SB 11000TLUS-12',
                    9152 => 'SB 7000TLUS-12',
                    9153 => 'SB 6000TLUS-12',
                    9154 => 'SB 1300TL-10',
                    9155 => 'Sunny Backup 2200',
                    9156 => 'Sunny Backup 5000',
                    9157 => 'Sunny Island 2012',
                    9158 => 'Sunny Island 2224',
                    9159 => 'Sunny Island 5048',
                    9160 => 'SB 3600TL-20',
                    9161 => 'SB 3000TL-JP-22',
                    9162 => 'SB 3500TL-JP-22',
                    9163 => 'SB 4000TL-JP-22',
                    9164 => 'SB 4500TL-JP-22',
                    9165 => 'SB 3600TL-21',
                    9167 => 'Cluster Controller',
                    9168 => 'SC630HE-11',
                    9169 => 'SC500HE-11',
                    9170 => 'SC400HE-11',
                    9171 => 'WB 3000TL-21',
                    9172 => 'WB 3600TL-21',
                    9173 => 'WB 4000TL-21',
                    9174 => 'WB 5000TL-21',
                    9175 => 'SC 250',
                    9176 => 'SMA Meteo Station',
                    9177 => 'SB 240-10',
                    9178 => 'SB 240-US-10',
                    9179 => 'Multigate-10',
                    9180 => 'Multigate-US-10',
                    9181 => 'STP 20000TLEE-10',
                    9182 => 'STP 15000TLEE-10',
                    9183 => 'SB 2000TLST-21',
                    9184 => 'SB 2500TLST-21',
                    9185 => 'SB 3000TLST-21',
                    9186 => 'WB 2000TLST-21',
                    9187 => 'WB 2500TLST-21',
                    9188 => 'WB 3000TLST-21',
                    9189 => 'WTP 5000TL-20',
                    9190 => 'WTP 6000TL-20',
                    9191 => 'WTP 7000TL-20',
                    9192 => 'WTP 8000TL-20',
                    9193 => 'WTP 9000TL-20',
                    9194 => 'STP 12000TL-US-10',
                    9195 => 'STP 15000TL-US-10',
                    9196 => 'STP 20000TL-US-10',
                    9197 => 'STP 24000TL-US-10',
                    9198 => 'SB 3000TLUS-22',
                    9199 => 'SB 3800TLUS-22',
                    9200 => 'SB 4000TLUS-22',
                    9201 => 'SB 5000TLUS-22',
                    9202 => 'WB 3000TLUS-22',
                    9203 => 'WB 3800TLUS-22',
                    9204 => 'WB 4000TLUS-22',
                    9205 => 'WB 5000TLUS-22',
                    9206 => 'SC 500CP-JP',
                    9207 => 'SC 850CP',
                    9208 => 'SC 900CP',
                    9209 => 'SC 850 CP-US',
                    9210 => 'SC 900 CP-US',
                    9211 => 'SC 619CP',
                    9212 => 'SMA Meteo Station',
                    9213 => 'SC 800 CP-US',
                    9214 => 'SC 630 CP-US',
                    9215 => 'SC 500 CP-US',
                    9216 => 'SC 720 CP-US',
                    9217 => 'SC 750 CP-US',
                    9218 => 'SB 240 Dev',
                    9219 => 'SB 240-US BTF',
                    9220 => 'Grid Gate-20',
                    9221 => 'SC 500 CP-US/600V',
                    9222 => 'STP 10000TLEE-JP-10',
                    9223 => 'Sunny Island 6.0H',
                    9224 => 'Sunny Island 8.0H',
                    9225 => 'SB 5000SE-10',
                    9226 => 'SB 3600SE-10',
                    9227 => 'SC 800CP-JP',
                    9228 => 'SC 630CP-JP',
                    9229 => 'WebBox-30',
                    9230 => 'Power Reducer Box',
                    9231 => 'Sunny Sensor Counter',
                    9232 => 'Sunny Boy Control',
                    9233 => 'Sunny Boy Control Plus',
                    9234 => 'Sunny Boy Control Light',
                    9235 => 'Sunny Central 100 Outdoor',
                    9236 => 'Sunny Central 1000MV',
                    9237 => 'Sunny Central 100 LV',
                    9238 => 'Sunny Central 1120MV',
                    9239 => 'Sunny Central 125 LV',
                    9240 => 'Sunny Central 150',
                    9241 => 'Sunny Central 200',
                    9242 => 'Sunny Central 200 HE',
                    9243 => 'Sunny Central 250 HE',
                    9244 => 'Sunny Central 350',
                    9245 => 'Sunny Central 350 HE',
                    9246 => 'Sunny Central 400 HE',
                    9247 => 'Sunny Central 400MV',
                    9248 => 'Sunny Central 500 HE',
                    9249 => 'Sunny Central 500MV',
                    9250 => 'Sunny Central 560 HE',
                    9251 => 'Sunny Central 630 HE',
                    9252 => 'Sunny Central 700MV',
                    9253 => 'Sunny Central',
                    9254 => 'Sunny Island 3324',
                    9255 => 'Sunny Island 4.0M',
                    9256 => 'Sunny Island 4248',
                    9257 => 'Sunny Island 4248U',
                    9258 => 'Sunny Island 4500',
                    9259 => 'Sunny Island 4548U',
                    9260 => 'Sunny Island 5.4M',
                    9261 => 'Sunny Island 5048U',
                    9262 => 'Sunny Island 6048U',
                    9263 => 'Sunny Mini Central 7000HV-11',
                    9264 => 'Sunny Solar Tracker',
                    9265 => 'Sunny Beam',
                    9266 => 'Sunny Boy SWR 700/150',
                    9267 => 'Sunny Boy SWR 700/200',
                    9268 => 'Sunny Boy SWR 700/250',
                    9269 => 'Sunny WebBox für SC',
                    9270 => 'Sunny WebBox',
                    9271 => 'STP 20000TLEE-JP-11',
                    9272 => 'STP 10000TLEE-JP-11',
                    9273 => 'SB 6000TL-21',
                    9274 => 'SB 6000TL-US-22',
                    9275 => 'SB 7000TL-US-22',
                    9276 => 'SB 7600TL-US-22',
                    9277 => 'SB 8000TL-US-22',
                    9278 => 'SI 3.0M',
                    9279 => 'SI 4.4M',
                    9281 => 'STP 10000TL-20',
                    9282 => 'STP 11000TL-20',
                    9283 => 'STP 12000TL-20',
                    9284 => 'STP 20000TL-30',
                    9285 => 'STP 25000TL-30',
                    9286 => 'SCS-500',
                    9287 => 'SCS-630',
                    9288 => 'SCS-720',
                    9289 => 'SCS-760',
                    9290 => 'SCS-800',
                    9291 => 'SCS-850',
                    9292 => 'SCS-900',
                    9293 => 'SB 7700TL-US-22',
                    9294 => 'SB20.0-3SP-40',
                    9295 => 'SB30.0-3SP-40',
                    9296 => 'SC 1000 CP',
                    9297 => 'Zeversolar 1000',
                    9298 => 'SC 2200-10',
                    9299 => 'SC 2200-US-10',
                    9300 => 'SC 2475-EV-10',
                    9301 => 'SB1.5-1VL-40',
                    9302 => 'SB2.5-1VL-40',
                    9303 => 'SB2.0-1VL-40',
                    9304 => 'SB5.0-1SP-US-40',
                    9305 => 'SB6.0-1SP-US-40',
                    9306 => 'SB8.0-1SP-US-40',
                    9307 => 'Energy Meter',
                    9308 => 'ZoneMonitoring',
                    9309 => 'STP 27kTL-US-10',
                    9310 => 'STP 30kTL-US-10',
                    9311 => 'STP 25kTL-JP-30',
                    9312 => 'SSM30',
                    9313 => 'SB50.0-3SP-40',
                    9314 => 'PlugwiseCircle',
                    9315 => 'PlugwiseSting',
                    9316 => 'SCS-1000',
                    9317 => 'SB 5400TL-JP-22',
                ]
            ],
            30057 => [
                'name' => 'Serial',
                'count' => 2,
                'type' => 'U32',
                'format' => 'RAW'
            ],
            30231 => [
                'name' => 'Power limit',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX0',
                'profile' => 'Watt'
            ],
            30837 => [
                'name' => 'Active power target value',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX0',
                'profile' => 'Watt'
            ]
        ],
        'sunnyboy' => [
            30231 => [
                'name' => 'Power limit',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX0',
                'profile' => 'Watt'
            ],
        ]
    ];

    /**
     * value addresses
     * addresses with updated values
     */
    const value_addresses = [
        'default' => [
            /**
             * Global
             */
            30201 => [
                'name' => 'Status',
                'count' => 2,
                'type' => 'U32',
                'format' => 'ENUM',
                'mapping' => [
                    35 => 'Error',
                    303 => 'Off',
                    307 => 'OK',
                    455 => 'Warning'
                ]
            ],
            30541 => [
                'name' => 'Operating time',
                'count' => 2,
                'type' => 'U32',
                'format' => 'DURATION_S',
                'profile' => 'Hours'
            ],
            30543 => [
                'name' => 'Feed-in time',
                'count' => 2,
                'type' => 'U32',
                'format' => 'DURATION_S',
                'profile' => 'Hours'
            ],
            30535 => [
                'name' => 'Day yield',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX3', // convert Wh to kWh
                'profile' => '~Electricity',
                'archive' => 1 // archive: counter
            ],
            30531 => [
                'name' => 'Total yield',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX0',
                'profile' => 'kWh.Fixed',
                'archive' => 1 // archive: counter
            ],
            30769 => [
                'name' => 'DC current input',
                'count' => 2,
                'type' => 'S32',
                'format' => 'FIX3',
                'profile' => '~Ampere'
            ],
            30771 => [
                'name' => 'DC voltage input',
                'count' => 2,
                'type' => 'S32',
                'format' => 'FIX2',
                'profile' => '~Volt'
            ],
            30773 => [
                'name' => 'DC power input',
                'count' => 2,
                'type' => 'S32',
                'format' => 'FIX0',
                'profile' => 'Watt'
            ],
            30775 => [
                'name' => 'AC active power across all phases',
                'count' => 2,
                'type' => 'S32',
                'format' => 'FIX0',
                'profile' => 'Watt',
                'archive' => 0 // archive: default
            ],
            30789 => [
                'name' => 'Grid voltage phase AB',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX2',
                'profile' => '~Volt'
            ],
            30791 => [
                'name' => 'Grid voltage phase BC',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX2',
                'profile' => '~Volt'
            ],
            30793 => [
                'name' => 'Grid voltage phase CA',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX2',
                'profile' => '~Volt'
            ],
            30795 => [
                'name' => 'Grid current',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX3',
                'profile' => '~Ampere'
            ],
            30803 => [
                'name' => 'Power frequency',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX2',
                'profile' => '~Hertz'
            ],
            /*
            34109 => [
                'name' => 'Heat sink temperature',
                'count' => 2,
                'type' => 'S32',
                'format' => 'TEMP',
                'profile' => '~Temperature'
            ],
            34113 => [
                'name' => 'Interior temperature',
                'count' => 2,
                'type' => 'S32',
                'format' => 'TEMP',
                'profile' => '~Temperature'
            ],
            34125 => [
                'name' => 'External temperature',
                'count' => 2,
                'type' => 'S32',
                'format' => 'TEMP',
                'profile' => '~Temperature'
            ],
            */
            /**
             * Sunny Tripower
             */
            30777 => [
                'name' => 'Power L1',
                'count' => 2,
                'type' => 'S32',
                'format' => 'FIX0',
                'profile' => 'Watt'
            ],
            30779 => [
                'name' => 'Power L2',
                'count' => 2,
                'type' => 'S32',
                'format' => 'FIX0',
                'profile' => 'Watt'
            ],
            30781 => [
                'name' => 'Power L3',
                'count' => 2,
                'type' => 'S32',
                'format' => 'FIX0',
                'profile' => 'Watt'
            ],
            30783 => [
                'name' => 'Grid Voltage phase L1',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX2',
                'profile' => '~Volt'
            ],
            30785 => [
                'name' => 'Grid Voltage phase L2',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX2',
                'profile' => '~Volt'
            ],
            30787 => [
                'name' => 'Grid Voltage phase L3',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX2',
                'profile' => '~Volt'
            ],
            30797 => [
                'name' => 'Grid current phase L1',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX3',
                'profile' => '~Ampere'
            ],
            30799 => [
                'name' => 'Grid current phase L2',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX3',
                'profile' => '~Ampere'
            ],
            30801 => [
                'name' => 'Grid current phase L3',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX3',
                'profile' => '~Ampere'
            ],

            /**
             * Sunny Island
             */
            30061 => [
                'name' => 'Firmware',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FW',
            ],
            30577 => [
                'name' => 'Grid energy consumption today',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX3',
                'profile' => '~Electricity'
            ],
            30579 => [
                'name' => 'Grid energy feed-in today',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX3',
                'profile' => '~Electricity'
            ],
            30581 => [
                'name' => 'Grid reference counter reading',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX3',
                'profile' => '~Electricity'
            ],
            30583 => [
                'name' => 'Grid feed-in counter reading',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX3',
                'profile' => '~Electricity'
            ],
            30845 => [
                'name' => 'Current battery charge status',
                'count' => 2,
                'type' => 'U32',
                'format' => 'RAW',
                'profile' => '~Battery.100'
            ],
            30849 => [
                'name' => 'Battery temperature',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX1',
                'profile' => '~Temperature'
            ],
            30851 => [
                'name' => 'Battery voltage',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX2',
                'profile' => '~Volt'
            ],
            30853 => [
                'name' => 'Active battery charging mode',
                'count' => 2,
                'type' => 'U32',
                'format' => 'ENUM',
                'mapping' => [
                    1767 => 'Quick charge',
                    1768 => 'Full charge',
                    1769 => 'Compensation charge',
                    1770 => 'Maintenance charge',
                    2184 => 'Save energy while on mains'
                ]
            ],
            30857 => [
                'name' => 'Number of battery charge throughputs',
                'count' => 2,
                'type' => 'S32',
                'format' => 'FIX0',
            ],
            30859 => [
                'name' => 'Battery maintenance charge status',
                'count' => 2,
                'type' => 'U32',
                'format' => 'ENUM',
                'mapping' => [
                    803 => 'Inactive',
                    1771 => 'Charge with solar power',
                    1772 => 'Charge with solar and mains power',
                ]
            ],
            30861 => [
                'name' => 'Consumer power',
                'count' => 2,
                'type' => 'S32',
                'format' => 'FIX0',
                'profile' => '~Watt'
            ]
        ],
        'sunnyboy' => [

        ]
    ];

    /**
     * adresses for high frequent values
     */
    const current_addresses = [
        'default' => [
            30775 => [
                'name' => 'AC active power across all phases',
                'count' => 2,
                'type' => 'S32',
                'format' => 'FIX0',
                'profile' => 'Watt'
            ]
        ],
        'sunnyboy' => [
            30775 => [
                'name' => 'AC active power across all phases',
                'count' => 2,
                'type' => 'U32',
                'format' => 'FIX0',
                'profile' => 'Watt'
            ]
        ]
    ];
}