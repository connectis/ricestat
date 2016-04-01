<?php

$data = '1  2  Numerico  Tipo Alloggiato (16-17-18)  SI
2  10  Alfanumerico  Data di arrivo (gg/mm/aaaa)  SI
3  50  Alfanumerico  Cognome  Riempire blank
4  30  Alfanumerico  Nome  Riempire blank
5  1  Numerico  Sesso (1=maschio; 2=femmina)  SI
6  10  Alfanumerico  Data di nascita (gg/mm/aaaa)  SI
7  9  Numerico  Codice comune di nascita  SI (se in Italia)
8  2  Alfanumerico  Provincia di nascita  SI (se in Italia)
9  9  Numerico  Codice stato di nascita  SI
10  9  Numerico  Codice cittadinanza  SI
11  9  Numerico  Codice comune di residenza  SI (se in Italia)
12  2  Alfanumerico  Provincia di residenza  SI (se in Italia)
13  9  Numerico  Codice stato di residenza  SI
14  50  Alfanumerico  Indirizzo  Riempire blank
15  5  Alfanumerico  Codice tipo documento di identità  Riempire blank
16  20  Alfanumerico  Numero documento di identità  Riempire blank
17  9  Numerico  Luogo o Stato rilascio documento  Riempire blank
18  10  Alfanumerico  Data di partenza (gg/mm/aaaa)  SI (modalità 2)
19  30  Alfanumerico  Tipo Turismo  NO 
20  30  Alfanumerico  Mezzo di Trasporto  NO
21  3  Numerico  Camere occupate (**)  SI
22  3  Numerico  Camere disponibili (**)  SI
23  4  Numerico  Letti disponibili (**)  SI
24  1  Numerico  Tassa soggiorno (1=sì; 0=no)  NO
25  10  Alfanumerico  Codice identificativo posizione (***)  SI
26  1  Numerico  Modalità  (1=Nuovo;  2=Variazione; 
3=Eliminazione)
SI';

foreach ( preg_split( '/\r?\n/is', $data ) as $line ) {
    print_r( preg_split( '/\t/is', $line ) );
}


