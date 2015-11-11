# ricestat
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

Usando un client REST tipo l'estensione per Chrome: Advanced REST client 
E'possibile fare delle prove.
Di seguito un esempio di Richiesta XML per leggere i clienti comunicati in un dato periodo.

```
<?xml version="1.0" encoding="UTF-8"?>
<MTO_SchedineRQ>
  <POS>
    <Source>
      <RequestorID ID="PmsProva" MessagePassword="ProvaProva" Type="4"/>
    </Source>
    <Source>
      <RequestorID ID="052004ALBDEMO" MessagePassword="password" Type="10"/>
    </Source>
  </POS>
  <RequestSegments>
    <RequestSegment>
      <SearchCriteria>
        <Criterion>
          <DateRange End="2014-03-20" Start="2013-03-20"/>
        </Criterion>
      </SearchCriteria>
    </RequestSegment>
  </RequestSegments>
</MTO_SchedineRQ>
```

#1. Introduzione
##1.1 Finalità
Questo documento descrive l’interfaccia XML in uso alle piattaforme Ricestat ed Unicom.
##1.2 Destinatari del documento
Questo documento è prodotto per quei soggetti che hanno esperienza nelle procedure XML e di programmazione in generale
Nel dettaglio è raccomandato che gli sviluppatori abbiano le seguenti competenze:
* Comprensione delle tecnologie di base dell’XML e degli schemi XML (Obbligatorio)
* Conoscenza delle tecnologie Internet quali http e HTTPS (Obbligatorio)

##1.3 A cosa serve l’interfaccia?
L’interfaccia XML permette a terze parti (denominate Gestionali da qui in poi) di connettersi con le piattaforme Ricestat e Unicom al fine di:
* Recuperare elenco e dettaglio delle singole regole del Regolamento del Comune Convenzionato
* Inviare all’Ente delegato i dati Istat della Struttura Ricettiva.
* Inviare i dati validi per l’Imposta di Soggiorno.
* Inviare ulteriori dati statistici.
* Recuperare conteggio riepilogativo dell’ Imposta di Soggiorno della struttura ricettiva.
#2. Unicom e Ricestat
Ricestat è la piattaforma in uso presso alcune province toscane (Arezzo, Grosseto, Livorno, Lucca, Massa Carrara, Pisa, Siena) per la ricezione dei dati Istat.
Unicom è una piattaforma in uso presso alcuni comuni Italiani (per l’elenco completo si rimanda  a relativo allegato) che fra le altre cose permette anche la ricezione dei dati Istat, Imposta di soggiorno e Offerta Turistica.
Le due piattaforme possono dialogare quindi entrambe sono in grado di interfacciarsi con i gestionali e condividere i dati di comune interesse.
#3.  Iniziamo
##3.1 Registrazione
Per poter utilizzare il servizio è necessario che il Gestionale sia certificato da Connectis. Affinche ciò sia possibile è necessario inviare una mail a support@ricestat.it indicando:
* Label (Nome sintetico del Gestionale) da mostrare agli operatori delle Strutture Ricettive.
* Nome di un referente (di contatto, in caso di problemi)
* Email del referente
* Codice struttura di un cliente da usare come base per i test
* Un numero di telefono di reperibilità in orario d'ufficio

A seguito di ricezione dei dati richiesti sarà inviata una mail con :
* IdCode e password di test per il gestionale
* Url (endopoint) di test

Una volta effettuati i test di compatibilità del sistema saranno rilasciati i dati di uso per la piattaforma in produzione.
##3.2 Come sono mandate le richieste
E’ specificato un url (endpoint) che svolge il compito di webservice e accetta le richieste XML tramite chiamate POST con protocollo XML
##3.3 Autenticazione
Usiamo uno dei metodi standard OTA di autenticazione  chiamato “POS” 
La richiesta di autenticazione è composta da due elementi:
* L’autenticazione della terza parte: l’ID Code  a password ottenuti in fase di registrazione dal Gestionale (vedi sopra)
* L’autenticazione della Struttura Ricettiva (S.R.): Il codice utente della S.R. (l che usano per entrare nella Extranet della piattaforma)  e la password di accesso allo stesso. 
