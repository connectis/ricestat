# Ricestat
Con il seguente progetto si cerca di definire un protocollo univoco per lo scambio delle informazioni del movimento turistico 
con gli enti pubblici che lo richiedono (Questura, Istat, Comuni).
Si sono prese alcune decisioni di base come specifiche di partenza:
* Le specifiche di Alloggiatiweb (Il portale della Questura dedicato allo scopo) rappresentano l'ossatura da cui parte lo sviluppo
* Le codifiche nazioni/comuni che si considerano sono quelle di Alloggiati Web. Sarà cura dei vari enti (o delegati) convertirli nei formati Istat.
* Si sono ridefinite le specifiche utilizzando il formato xml 
* Si sono integrate le variabili necessarie per gli adempimenti Istat
* Si sono integrate le variabili necessarie per l'Imposta di soggiorno

Nasce dall'iniziativa di Connectis in ambito al progetto UPI Toscana per una ottimizzazione delle procedure a livello Regionale, 
ma si cerca con la pubblicazione su GitHub di far sì che possano partecipare tutti gli attori della filiera e che il protocollo 
possa poi essere esteso all'esterno della Regione Toscana.

Sono invitati a partecipare al progetto tutti gli sviluppatori di software GESTIONALI del settore Ricettivo e gli sviluppatori 
di software per gli Enti Pubblici di tutta Italia.


L'end point di test è il seguente:
http://test.motouristoffice.it/MTO_SchedinaRQ.php

Usando un client REST tipo l'estensione per Chrome: Advanced REST client http://chromerestclient.appspot.com/
E'possibile fare delle prove.
I file che iniziano con esempio riportano una serie di esempi utili per interrogare il webservice.
I parametri del POS devono essere sostituiti con i propri personali forniti da Connectis, per non accavallarsi con altri sviluppatori.
Di seguito un esempio di Richiesta XML per leggere i clienti comunicati in un dato periodo. 

 
```xml
<?xml version="1.0" encoding="UTF-8"?>
<MTO_SchedineRQ>
<POS>
        <Source>
            <RequestorID Type = "4" ID = "PmsProva" MessagePassword = "ProvaProva"/>
        </Source>
        <Source>
            <RequestorID Type = "10" ID = "052004ALBDEMO" MessagePassword = "password"/>
        </Source>
</POS>
 <RequestSegments>
  <RequestSegment>
   <SearchCriteria>
    <Criterion>
     <DateRange Start="2013-03-20" End="2014-03-20" />
    </Criterion>
   </SearchCriteria>
  </RequestSegment>
 </RequestSegments>
</MTO_SchedineRQ>
```

# 1.	Introduzione
## 1.1	Finalità ##
Questo documento descrive l’interfaccia XML in uso alle piattaforme Ricestat ed Unicom. 
IL servizio è un servizio WEBSERVICE in Rest.
## 1.2	Destinatari del documento ##
Questo documento è prodotto per quei soggetti che hanno esperienza nelle procedure XML e di programmazione in generale
Nel dettaglio è raccomandato che gli sviluppatori abbiano le seguenti competenze:
*	Comprensione delle tecnologie di base dell’XML e degli schemi XML (Obbligatorio)
*	Conoscenza delle tecnologie Internet quali http e HTTPS (Obbligatorio)

## 1.3	A cosa serve l’interfaccia? ##
L’interfaccia XML permette a terze parti (denominate Gestionali da qui in poi) di connettersi con le piattaforme Ricestat e Unicom al fine di:
*	Recuperare elenco e dettaglio delle singole regole del Regolamento del Comune Convenzionato
*	Inviare all’Ente delegato i dati Istat della Struttura Ricettiva.
*	Inviare i dati validi per l’Imposta di Soggiorno.
*	Inviare ulteriori dati statistici.
*	Recuperare conteggio riepilogativo dell’ Imposta di Soggiorno della struttura ricettiva.

# 2.	Unicom e Ricestat
Ricestat è la piattaforma in uso presso alcune province toscane (Arezzo, Grosseto, Livorno, Lucca, Massa Carrara, Pisa, Siena) per la ricezione dei dati Istat.
Unicom è una piattaforma in uso presso alcuni comuni Italiani (per l’elenco completo si rimanda  a relativo allegato) che fra le altre cose permette anche la ricezione dei dati Istat, Imposta di soggiorno e Offerta Turistica.
Le due piattaforme possono dialogare quindi entrambe sono in grado di interfacciarsi con i gestionali e condividere i dati di comune interesse.

# 3. Iniziamo
## 3.1 Registrazione ##
Per poter utilizzare il servizio è necessario che il Gestionale sia certificato da Connectis. Affinche ciò sia possibile è necessario inviare una mail a support@ricestat.it indicando:
*	Label (Nome sintetico del Gestionale) da mostrare agli operatori delle Strutture Ricettive.
*	Nome di un referente (di contatto, in caso di problemi)
*	Email del referente
*	Codice struttura di un cliente da usare come base per i test
*	Un numero di telefono di reperibilità in orario d'ufficio

A seguito di ricezione dei dati richiesti sarà inviata una mail con :
*	IdCode e password di test per il gestionale
*	Url (endopoint) di test

Una volta effettuati i test di compatibilità del sistema saranno rilasciati i dati di uso per la piattaforma in produzione.

## 3.2 Come sono mandate le richieste ##
E’ specificato un url (endpoint) che svolge il compito di webservice e accetta le richieste XML tramite protocollo XML/SOAP, con HTTP binding 

## 3.3 Autenticazione ##
Usiamo uno dei metodi standard OTA di autenticazione  chiamato “POS” 
La richiesta di autenticazione è composta da due elementi:
*	L’autenticazione della terza parte: l’ID Code  a password ottenuti in fase di registrazione dal Gestionale (vedi sopra)
*	L’autenticazione della Struttura Ricettiva (S.R.): Il codice utente della S.R. (l che usano per entrare nella Extranet della piattaforma)  e la password di accesso allo stesso. 

## 3.4 Schema ##
webci.xsd è lo schema commentato che esprime le specifiche del flusso xml

# 4. Esempi
Sono caricati alcuni esempi d i flusso xml per poter testare le funzionalità del webservice e confrontare i risultati con quelli ritornati dal proprio software

## 4.1 Configurazione ##
Questi sono gli esempi per richiedere i parametri operativi per l'invio dei dati

### 4.1.1 esempio_lettura_comuni ###
Questo esempio permette di avere la lista dei codici comuni supportati dalla procedura (sono gli stessi previsti per la Questura)

### 4.1.2 esempio_lettura_stati ###
Questo esempio permette di avere la lista degli stati supportati dalla procedura (sono gli stessi previsti per la Questura)

### 4.1.3 esempio_lettura_mezzi ###
Questo esempio permette di avere la lista degli id delle informazioni opzionali statistiche riguardo  la registrazione dei mezzi di trasporto dei clienti

### 4.1.4 esempio_lettura_tipoprenotazione ###
Questo esempio permette di avere la lista degli id delle informazioni opzionali statistiche riguardo la registrazione delle modalità di prenotazione

### 4.1.5 esempio_lettura_tipoturismo ###
Questo esempio permette di avere la lista degli id delle informazioni opzionali statistiche riguardo la registrazione delle tipologia di turismo

### 4.1.6 Esempio_lettura_regolamento_imposta ###
Chiamando il servizio si accede alla lista delle estensioni previste dal regolamento comunale così come configurato e voluto dal comune stesso.
Per accedere alla lista di tutte le esenzioni potenzialmente configurabili dal comune la richiesta è la seguente:
`     <ImpostaSoggiorno Comune="*" />`
Per accedere invece al regolamento del Comune specifico la chiamata è la seguente:
`     <ImpostaSoggiorno Comune="409052015" />` (Nel caso del Comune di Montepulciano)
Qualora il risultato di questa chiamata sia nullo è perchè il comnue non ha ancora configurato il regolamento

## 4.2 Movimentazione ##
Esempi legati ai dati di movimentazione. Questi esempi si riferiscono alle procedure continuative che permettono la corretta gestione dei flussi di movimentazione

### 4.2.1 Esempio_inserimento ###
Procedura di inserimento delle movimentazioni giornaliere.

### 4.2.2 Esempio_lettura ###
Procedura di lettura delle informazione inserite, la lettura è per periodo, ma può essere anche per singolo Idutente (esempio_lettura_id) 

### 4.2.3 Esempio_cancellazione ###
Modalità di cancellazione per singolo ID, la risposta indica l'esito dell'evento

### 4.2.4 Esempio_aggiornamento ###
Modalità di aggiornamento è per singolo id Utente

# 5. Imposta di Soggiorno
## 5.1 Tag ImpostaDiSoggiorno ##

il tag imposta di soggiorno è sempre opzionale incluso i suoi sottotag:

>  <ImpostaDiSoggiorno>
>      <CodiceImpostaSoggiorno></CodiceImpostaSoggiorno>
>      <ValoreImpostaUnitaria></ValoreImpostaUnitaria>
>      <NottiImponibili></NottiImponibili>
>     </ImpostaDiSoggiorno>
 questo però non vuol dire che non mandandolo poi la comunicazione fornisca l'informazione corretta

Questo vuol dire che la compilazione dei 3 campi è sempre funzione del regolamento comunale e dell'operatività dell'albergo

Partiamo dal significato di ogni singolo tag e da cosa succede se non è presente o valorizzato.

## 5.2 Tag CodiceImpostaSoggiorno ##

Se è valorizzato rende l'utente esente. Se non è valorizzato è pagante (sempre che nel periodo sia prevista l'imposta). 

Se la valorizzazione non rientra in uno dei codici previsti dal regolamento comunale allora il sistema lo visualizza al gestore come esente, ma evidenziando in rosso che l'esenzione NON è definita in quel comune o per quella tipologia. 

I codici attivi per i vari comuni sono reperibili o tramite la chiamata API descritta in esempio_lettura_regolamento_imposta nella extranet della nostra applicazion eda parte del gestore. La procedura è pubblicata nella pagina seguente: https://wci.unicom.uno/esenzioni/imposta

Esempi:

Il codice ABB per esenzione in quanto incassato da AirBnB, ma non tutti i comuni hanno sottoscritto tale esenzione
A Pisa non ci sono esenzioni tranne i residenti e ABB

## 5.3 Tag  ValoreImpostaUnitaria ##

Se è valorizzato corrisponde al costo a persona a note, indipendentemente da quanto specificato nel regolamento. 

Se non specificato il sistema prende la tariffa massima prevista per quella tipologia di struttura per quel periodo. 

Se da regolamento è definita una sola tariffa per tipologia e classificazione per il periodo di interesse, il sistema prende sicuramente il valore giusto. 

Se invece stiamo parlando di un comune come Arezzo in cui le tariffe sono 3 e sono organizzate a fasce in funzione di quanto paga l'ospite a camera, se il PMS non invia la tariffa applicata all'ospite, il gestore rischierebbe di trovarsi applicata la tariffa errata (sicuramente maggiore).

## 5.4 Tag NottiImponibili ##

Se non lo si  valorizzate, noi prendiamo come dato quello fornito dal regolamento, cioè le x notti massime continuative che si pagano su quello specifico comune. 

Se invece lo valorizzate e il valore è superiore a quello definito dal regolamento comunale, teniamo come valido questo valore ma lo segnaliamo come sia al gestore, sia al comune come FORZATURA rispetto allo standard.  

Se lo valorizzate con un numero superiore a quello definito dal regolamento non lo consideriamo

Esempi:

Su Grosseto si pagano le prime 14 notti usufruite nell'anno (non le consecutive). Serve per i clienti di ritorno. 

Se un cliente viene tutti i fine settimana per 3 giorni nel periodo Maggio, Settembre, il PMS mi può mandare il residuo dei giorni da usufruire per ogni soggiorno  14 ; 11; 8; 5; 2; 0 ; 0 ; ... e così via. Oppure 14; 14; 14;14; 2 ; 0 ; 0; ... e così via. Questo a vostra discrezione in base a a come avete implementato questa specifica nel vostro  PMS.

In altri comuni, esempio Pisa questo valore cambia in base al periodo di alta o bassa stagione (Fino alla domenica delle Palme si pagano i primi 3 continuativi, dopo la domenica delle Palme si pagano i primi 5. Se voi non valorizzate il tag, noi sappiamo quando scattano i giorni, se invece mi comunicate 5 in bassa stagione io prendo buono 3, se mi comunicate 3 in alta stagione prendo buono 3 (perchè inferiore) evidenziando l'anomalia. 

La domanda è: perchè prendo per buono 3? Perchè magari state mandando i dati di un ospite a cui avete cambiato stanza dopo 2 giorni e lo mandate come nuovo arrivo e non come modifica del precedente

In generale quindi questo tag non serve tranne in pochi casi particolari, ma è messo a disposizione del PMS e del gestore per poter gestire anche quelli



# 6. Tool extra
## 6.1 Tool di conversione fra schedina Questura e Ricestat ##
Il seguente tool sviluppato in php vs>=5.3 consiste in una webform che converte il file txt della questura nel flusso XML del sistema.
E' rilasciato a solo scopo di esempio in quanto le variabili identificative di Id Ospite, Id Gruppo e Id Camera non sono specificate nel file Questura e quindi sono inserite ramndom (oltre alle informazioni di provenienza). Il tool è però utile per poter visualizzare una possibile implementazione del sistema di accesso al webservice.
Per accedere alla home chiamare la pagina index.php sotto la cartella web

## 6.1  Tool di conversione fra file generati con protocollo SIRED (Sardegna, Rimini, Pistoia) e invio a Ricestat/Unicom
Il seguente tool sviluppato in php vs>=5.3 consiste in una webform che converte il file txt in uso presso gli enti in oggetto nel flusso XML del sistema.
E' rilasciato a scopo di esempio. Il tool è però utile per poter essere implementato in modalità open source per chi ne necessitasse.
Per accedere alla home chiamare la pagina index.php sotto la cartella htdocs.
Esiste una versione pubblicata funzionante all'indirizzo www.unicom.tools. Il link può essere comunicato ai clienti, ma è comunque necessario richiedere le credenziali per il dialogo col webserver.

# 7. Feedback
Questa sezione contiene feedback rilasciati dagli sviluppatori dei gestionali a beneficio dei colleghi affinchè possano prendere spunto dalle loro esperienze. Ringraziamo e riportiamo i loro riferimenti.
## 7.1  Smartmedia 2000 ##
Gestionale per Campeggi. Referente ing. Fabrizio Felici
Smartmedia 2000  
via Lituania, 46 - 58100 Grosseto Italia
tel +39 347 6444150  p.iva 01208620532
http://www.smartmedia2000.it/ Pec info@pec.smartmedia2000.it
