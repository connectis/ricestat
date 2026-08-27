# Ricestat

**Il protocollo XML per la comunicazione del movimento turistico** — rilevazione
statistica Istat e imposta di soggiorno.

**Versione del protocollo: 1.0.** Questa pagina è aggiornata al 27 agosto 2026; che cosa è
cambiato e quando è nello [storico delle versioni](#storico-delle-versioni).

> **Le istruzioni e gli esempi non sono qui: sono sul portale.**
> **→ <https://ws.unicom.uno/>**
>
> Su questa pagina trovi da dove viene il protocollo, dove si usa e come accedere alla
> documentazione viva. Il materiale che c'era prima — procedure ed esempi — è conservato
> [in fondo](#materiale-storico), ma è obsoleto e non fa testo.

---

## 1. Da dove viene

Il progetto nasce per definire un protocollo univoco per lo scambio delle informazioni del
movimento turistico con gli enti pubblici che le richiedono. Nasce dall'iniziativa di
Connectis nell'ambito del progetto UPI Toscana, per ottimizzare le procedure a livello
regionale, e viene pubblicato su GitHub perché possano parteciparvi tutti gli attori della
filiera e perché possa essere esteso oltre la Toscana.

Le decisioni di partenza, che spiegano perché il tracciato è fatto così:

* le specifiche di **AlloggiatiWeb** — il portale della Questura — sono l'ossatura da cui
  parte lo sviluppo;
* le **codifiche di nazioni e comuni** sono quelle di AlloggiatiWeb; sarà cura dei vari
  enti, o dei loro delegati, convertirle nei formati Istat;
* le specifiche sono state ridefinite in **XML**;
* sono state integrate le variabili necessarie agli **adempimenti Istat**;
* sono state integrate le variabili necessarie all'**imposta di soggiorno**.

Il protocollo è in esercizio da oltre dieci anni ed è cresciuto per aggiunte successive:
il tracciato di base è rimasto quello, e chi lo implementò allora continua a funzionare.

> **Attenzione a un nome che inganna.** Nel tracciato compaiono `InserimentoAlloggiati`,
> `Alloggiato`, `TipoAlloggiato`, ma i dati che viaggiano qui servono alla **rilevazione
> statistica del movimento turistico e all'imposta di soggiorno**. Non hanno nulla a che
> vedere con la comunicazione delle schedine alla **Questura** (portale AlloggiatiWeb),
> che resta un adempimento distinto e non passa da qui.

## 2. Dove si usa, e chi può usarlo

**Il protocollo è liberamente usabile.** Sono invitati a partecipare tutti gli
sviluppatori di software gestionale del settore ricettivo e gli sviluppatori di software
per gli enti pubblici, di tutta Italia.

Non è il protocollo di un singolo portale: è **lo stesso web service** che serve tutte le
piattaforme qui sotto.

| | | |
|---|---|---|
| **Unicom** | **Motouristoffice** | **Ricestat** |
| **MTO** | **WebCheckin** | **FlashID** |

Questo ha una conseguenza pratica che conviene mettere a fuoco subito: **ci si integra una
volta sola.** Un albergatore può chiedervi il collegamento a un portale con un nome
diverso da quello su cui vi siete certificati, ma **protocollo ed endpoint restano gli
stessi** — cambiano soltanto le credenziali della struttura. Non serve alcuna
integrazione aggiuntiva.

Ricestat era la piattaforma in uso presso alcune province toscane (Arezzo, Grosseto,
Livorno, Lucca, Massa Carrara, Pisa, Siena) per la ricezione dei dati Istat fino a Genniao 2026. E' stata sostituita da Motouristoffice. Unicom è in
uso presso vari comuni italiani e riceve, oltre ai dati Istat, l'imposta di soggiorno e
l'offerta turistica. Le piattaforme dialogano fra loro e condividono i dati di comune
interesse.

## 3. Il portale: è lì che si lavora

**→ <https://ws.unicom.uno/>**

C'è un portale dedicato a chi integra il protocollo, e **la documentazione che fa testo è
quella**. Sul portale, dopo una registrazione che si fa da sé e senza attendere risposta
da nessuno, si trovano:

* le **istruzioni aggiornate** e la documentazione API, anche in forma interattiva;
* gli **esempi**, già compilati con le proprie credenziali di prova;
* un **ambiente sandbox** dove provare quante volte si vuole, senza toccare la produzione;
* la **suite di test**, che dice quali prove mancano e che cosa ha risposto il sistema a
  ciascun invio;
* l'**indirizzo del web service** assegnato al proprio account;
* il **passaggio in produzione automatico** al superamento delle prove obbligatorie.

### Il materiale su GitHub è funzionante, ma obsoleto

Le procedure e gli esempi pubblicati qui **continuano a funzionare**: l'endpoint storico
risponde ancora e i flussi XML sono ancora quelli. Non sono stati spenti e non lo saranno
a breve.

Ma sono fermi a prima del portale, e non contengono le funzioni aggiunte in seguito. Se
li usi come riferimento resti indietro senza accorgertene, perché nulla ti segnala che
esiste una versione più recente.

**Quindi: registrati sul portale e lavora lì.** Questa pagina serve a mandarti là.

## 4. Lo schema

Lo schema commentato che esprime le specifiche del flusso XML è pubblicato a un indirizzo
**versionato**, ed è l'unico indirizzo da usare:

**<https://ws.unicom.uno/webci-1.0.xsd>**

Resta pubblicato anche `https://ws.unicom.uno/webci.xsd`, come alias di cortesia per chi
lo ha già cablato: è **congelato sulla 1.0** e non è «l'ultima versione». Il giorno che
uscirà la 2.0, quell'indirizzo resterà fermo dov'è e la 2.0 sarà `webci-2.0.xsd` — così
nessuno si ritrova validato contro uno schema che non ha scelto. Oggi i due file sono
identici byte per byte.

Ogni messaggio dichiara la versione con cui è costruito: l'attributo `Version`
sull'elemento radice è **obbligatorio** e oggi ammette **un solo valore, `1.0`**. Un
messaggio di un'altra versione va validato contro lo schema di quella versione.

> **Nota, per chi legge le risposte del servizio storico.** Quel servizio dichiara al suo
> interno un terzo indirizzo per lo schema (`http://ws.webci.it/webci.xsd`), diverso da
> quelli qui sopra. È un disallineamento noto: l'indirizzo da usare è quello versionato.

---

# Storico delle versioni

Le voci più recenti per prime. La **versione del protocollo** è quella dichiarata
nell'attributo `Version` dei messaggi; le date sono quelle di aggiornamento di questa
pagina.

## Protocollo 1.0

### 27 agosto 2026

* **Questa pagina cambia natura**: non è più un manuale ma un rimando al portale, dove
  stanno istruzioni ed esempi aggiornati. Il contenuto precedente è conservato in
  [Materiale storico](#materiale-storico).
* **Registrazione autonoma**: ci si iscrive dal portale, senza mandare mail né attendere
  che qualcuno risponda con le credenziali.
* **Rilascio in produzione automatico** al superamento delle prove obbligatorie.
* **Schema a indirizzo versionato**: `webci-1.0.xsd`; `webci.xsd` resta come alias
  congelato sulla 1.0.
* **Nominate tutte le piattaforme** che usano questo web service: chi si certifica una
  volta è compatibile con tutte.
* Corretto: `<Aggiornamento>`, `<Eliminazione>` e `<RequestSegments>` **non sono «in
  roadmap»** — sono implementate da anni e sono fra le prove obbligatorie. Le versioni
  precedenti di questa pagina le davano come future.
* Aggiunte al protocollo, documentate sul portale: le **API opzionali** (dichiarazione
  dei mesi senza movimenti o di chiusura, e richiesta delle presenze del periodo),
  riservate a chi è certificato per l'imposta di soggiorno nei comuni in cui la gestiamo.

### Prima del 2026

Versioni precedenti di questa pagina, senza storico. Le differenze note rispetto a oggi
sono quelle elencate qui sopra.

## Protocollo 2.0

Non ancora pubblicata. Quando uscirà avrà il proprio schema (`webci-2.0.xsd`) e la propria
sezione in questo storico, e i messaggi la dichiareranno con `Version="2.0"`. La 1.0
resterà valida e documentata.

---

# Materiale storico

> ## ⚠ Da qui in giù è materiale storico, e non fa testo
>
> È il contenuto che questa pagina aveva prima di agosto 2026. **Non è aggiornato**:
> descrive una procedura di accesso che non è più quella, non contiene le funzioni
> aggiunte in seguito e in qualche punto dice cose che non valgono più.
>
> È conservato per chi ci arriva da un collegamento vecchio e per non perdere memoria di
> come funzionava. **Parte di questo materiale è tuttora corretto** — le regole
> sull'imposta di soggiorno, per esempio, sono in buona sostanza quelle — ma **la versione
> che fa testo è quella sul portale**: <https://ws.unicom.uno/certif/>.
>
> Non usarlo come riferimento per una nuova integrazione.

## [storico] Registrazione

> **Non è più così.** Oggi ci si registra da sé sul portale. L'indirizzo di posta indicato
> qui sotto non è più la via per iniziare.

Per poter utilizzare il servizio era necessario che il gestionale fosse certificato da
Connectis. Si inviava una mail indicando:

* label (nome sintetico del gestionale) da mostrare agli operatori delle strutture
  ricettive;
* nome di un referente di contatto, in caso di problemi;
* e-mail del referente;
* codice struttura di un cliente da usare come base per i test;
* un numero di telefono di reperibilità in orario d'ufficio.

A seguito della ricezione dei dati veniva inviata una mail con l'IdCode e la password di
test per il gestionale e l'url (endpoint) di test. Una volta effettuati i test di
compatibilità venivano rilasciati i dati di accesso alla piattaforma di produzione.

## [storico] Come sono mandate le richieste

Un url (endpoint) svolge il compito di web service e accetta le richieste XML su HTTP.

L'endpoint di prova indicato allora era
`http://test.motouristoffice.it/MTO_SchedinaRQ.php`, e **risponde tuttora**. Non è però
l'indirizzo da usare: quello del proprio account lo assegna il portale.

## [storico] Autenticazione

Si usa uno dei metodi standard OTA di autenticazione, chiamato **POS**. La richiesta è
composta da due elementi:

* l'autenticazione della terza parte: l'IdCode e la password ottenuti in fase di
  registrazione dal gestionale;
* l'autenticazione della struttura ricettiva: il codice utente della S.R. — quello che usa
  per entrare nella extranet della piattaforma — e la relativa password.

Esempio di richiesta XML per leggere i clienti comunicati in un dato periodo:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<MTO_SchedineRQ Version="1.0" PrimaryLangID="it">
    <POS>
        <Source><RequestorID Type="4"  ID="IL_TUO_ID_CANALE"    MessagePassword="LaTuaPasswordCanale"/></Source>
        <Source><RequestorID Type="10" ID="IL_CODICE_STRUTTURA" MessagePassword="LaTuaPasswordStruttura"/></Source>
    </POS>
    <RequestSegments CodiceEsercizio="IL_CODICE_STRUTTURA">
        <RequestSegment>
            <SearchCriteria>
                <Criterion>
                    <DateRange Start="2026-03-20" End="2026-04-20"/>
                </Criterion>
            </SearchCriteria>
        </RequestSegment>
    </RequestSegments>
</MTO_SchedineRQ>
```

## [storico] Esempi

Nel repository sono caricati alcuni esempi di flusso XML per testare le funzionalità del
web service e confrontare i risultati con quelli ritornati dal proprio software. **Gli
esempi aggiornati, già compilati con le proprie credenziali, sono sul portale.**

### [storico] Configurazione

Esempi per richiedere i parametri operativi per l'invio dei dati.

* `esempio_lettura_comuni` — la lista dei codici comuni supportati dalla procedura (sono
  gli stessi previsti per la Questura);
* `esempio_lettura_stati` — la lista degli stati supportati;
* `esempio_lettura_mezzi` — gli id delle informazioni statistiche sui mezzi di trasporto;
* `esempio_lettura_tipoprenotazione` — gli id delle modalità di prenotazione;
* `esempio_lettura_tipoturismo` — gli id delle tipologie di turismo;
* `esempio_lettura_regolamento_imposta` — le esenzioni previste dal regolamento comunale,
  così come configurato dal comune stesso. Si interroga un comune per volta, indicandone
  il codice ISTAT di nove cifre:

  ```xml
  <ImpostaSoggiorno Comune="409052015"/>
  ```

  (nel caso del Comune di Montepulciano). Se il risultato è nullo, il comune non ha ancora
  configurato il regolamento.

### [storico] Movimentazione

Esempi legati ai flussi di movimentazione, cioè alle procedure continuative.

* `Esempio_inserimento` — inserimento delle movimentazioni giornaliere;
* `Esempio_lettura` — lettura delle informazioni inserite, per periodo oppure per singolo
  id utente (`esempio_lettura_id`);
* `Esempio_cancellazione` — cancellazione per singolo id; la risposta indica l'esito;
* `Esempio_aggiornamento` — aggiornamento per singolo id utente.

## [storico] Imposta di soggiorno

> Questa parte è **in buona sostanza ancora valida**, ed è la ragione principale per cui
> il materiale storico non viene cancellato. La versione aggiornata sta sul portale.

### Tag `ImpostaDiSoggiorno`

Il tag è sempre opzionale, inclusi i suoi sottotag:

```xml
<ImpostaDiSoggiorno>
    <CodiceImpostaSoggiorno></CodiceImpostaSoggiorno>
    <ValoreImpostaUnitaria></ValoreImpostaUnitaria>
    <NottiImponibili></NottiImponibili>
</ImpostaDiSoggiorno>
```

Questo però non vuol dire che non mandandolo la comunicazione fornisca l'informazione
corretta: la compilazione dei tre campi è sempre funzione del regolamento comunale e
dell'operatività dell'albergo.

### Tag `CodiceImpostaSoggiorno`

Se è valorizzato rende l'ospite **esente**; se non è valorizzato è **pagante**, sempre che
nel periodo sia prevista l'imposta.

Se la valorizzazione non rientra fra i codici previsti dal regolamento comunale, il sistema
mostra l'ospite al gestore come esente, ma evidenzia in rosso che l'esenzione **non** è
definita in quel comune o per quella tipologia.

I codici attivi per i vari comuni si ottengono con la chiamata
`esempio_lettura_regolamento_imposta`, oppure dalla extranet a cura del gestore. La
procedura è pubblicata su <https://wci.unicom.uno/esenzioni/imposta>.

Per esempio il codice `ABB`, per l'esenzione in quanto imposta già incassata da AirBnB:
non tutti i comuni hanno sottoscritto tale esenzione. A Pisa non ci sono esenzioni tranne
i residenti e ABB.

### Tag `ValoreImpostaUnitaria`

Se è valorizzato corrisponde al costo a persona a notte, indipendentemente da quanto
specificato nel regolamento. È un numero decimale con il **punto**, non con la virgola.

Se non è specificato, il sistema prende la tariffa massima prevista per quella tipologia
di struttura in quel periodo.

Se da regolamento è definita una sola tariffa per tipologia e classificazione nel periodo
di interesse, il sistema prende sicuramente il valore giusto. Se invece si tratta di un
comune come Arezzo, dove le tariffe sono tre e organizzate a fasce in funzione di quanto
paga l'ospite a camera, e il PMS non invia la tariffa applicata, il gestore rischia di
trovarsi applicata la tariffa errata — sicuramente maggiore.

### Tag `NottiImponibili`

Se non lo si valorizza, viene preso il dato del regolamento: le *x* notti massime
continuative che si pagano in quel comune.

Se lo si valorizza con un valore superiore a quello definito dal regolamento, quel valore
viene tenuto come valido ma segnalato al gestore e al comune come **forzatura** rispetto
allo standard.

A Grosseto si pagano le prime 14 notti usufruite nell'anno, non le consecutive: serve per
i clienti di ritorno. Se un cliente torna tutti i fine settimana per tre giorni da maggio
a settembre, il PMS può mandare il residuo dei giorni da usufruire per ogni soggiorno —
14; 11; 8; 5; 2; 0; 0; … — oppure 14; 14; 14; 14; 2; 0; 0; … a propria discrezione, in
base a come ha implementato la specifica.

In altri comuni il valore cambia con la stagione. A Pisa, fino alla domenica delle Palme si
pagano i primi 3 giorni continuativi, dopo i primi 5. Se il tag non è valorizzato, il
sistema sa quando scattano i giorni; se gli si comunica 5 in bassa stagione prende buono
3, e se gli si comunica 3 in alta stagione prende buono 3 perché inferiore, evidenziando
l'anomalia. Perché prende per buono 3? Perché potrebbero essere i dati di un ospite a cui
è stata cambiata stanza dopo due giorni e che viene mandato come nuovo arrivo anziché come
modifica del precedente.

In generale questo tag non serve, tranne in pochi casi particolari, ma è a disposizione
del PMS e del gestore per poterli gestire.

## [storico] Tool extra

### Conversione fra schedina Questura e Ricestat

Sviluppato in PHP >= 5.3, è una webform che converte il file txt della Questura nel flusso
XML del sistema. È rilasciato a solo scopo di esempio: le variabili identificative di id
ospite, id gruppo e id camera non sono presenti nel file della Questura e vengono quindi
inserite in modo casuale, così come le informazioni di provenienza. Resta utile per
vedere una possibile implementazione dell'accesso al web service. Per accedere alla home,
chiamare `index.php` sotto la cartella `web`.

### Conversione fra file SIRED e Ricestat/Unicom

Sviluppato in PHP >= 5.3, è una webform che converte il file txt in uso presso gli enti
che adottano il protocollo SIRED (Sardegna, Rimini, Pistoia) nel flusso XML del sistema.
È rilasciato a scopo di esempio, ma può essere implementato in modalità open source da chi
ne avesse necessità. Per accedere alla home, chiamare `index.php` sotto la cartella
`htdocs`.

Esiste una versione pubblicata funzionante su <http://www.unicom.tools>. Il link può essere
comunicato ai clienti, ma restano necessarie le credenziali per il dialogo col web service.

## [storico] Feedback

Questa sezione conteneva i feedback rilasciati dagli sviluppatori dei gestionali a
beneficio dei colleghi, affinché potessero prendere spunto dalle loro esperienze. Li
ringraziamo e ne riportiamo i riferimenti.

### Smartmedia 2000

Gestionale per campeggi. Referente ing. Fabrizio Felici.
Smartmedia 2000, via Lituania 46 — 58100 Grosseto, Italia.
Tel. +39 347 6444150 — P.IVA 01208620532
<http://www.smartmedia2000.it/> — PEC info@pec.smartmedia2000.it
