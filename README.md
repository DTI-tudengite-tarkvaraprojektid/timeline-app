# Timeline app

 ![Screenshot](/assets/screenshot1.png?raw=true "Screenshot")

---

## Eesmärk või lühikirjeldus

Eesmärgiks oli luua tööriist kuhu saaks instituudi liikmed lisada tekste, fotosid, videoid ja faile ning neid otsida märksõnade järgi. Luua ka ajajoon kuhu saaks lisada sündmusi ja sündmuste sisse pilte, faile ja videosi. Vajadus digiteerida instituudi ajaloo olulisemad sündmused. 

See projekt on loodud suve praktika raames Tallinna Ülikooli Digitehnoloogiate instituudis. Koduleht: [www.tlu.ee/dt](https://www.tlu.ee/dt)

---

## Kasutatud tehnoloogiad
- PHP : versioon 7.2
- MariaDB : versioon 10.1
- NodeJs : versioon 11.1
- Apache 2

**PHP teegid**

- Slim 3 : versioon 3.0
- Slim Flash : versioon 0.2.0
- Slim Validation : versioon 3.0
- Sentinel : versioon 2.0
- Slim twig view : versioon 2.0
- Eloquent : versioon 5.4

**NodeJs teegid**

- webpack : versioon 4.32
- bootstrap : versioon 4.3
- jquery : versioon 3.4
- quill : versioon 1.3
- tippy.js : versioon 4.3

---

## Arendajad
- Marvin Helstein
- Roland Vägi
- David Frederik Erlich
- Taavi Liivat
- Steven Saluri

---

## Eeltingimused
Selle projekt installimiseks peab sinu arenduskeskkonnas olema PHP 7.2, Node.js, composer ning MySQL või MariaDB.

## Arenduskeskkonna loomine

### Klooni projekt
Alusta projekti koodi hankimisega
``` bash
$ git clone https://bitbucket.org/CareFully/timeline-app.git
```

### Seadista keskkonnamuutujad

Kopeeri `.env.dist` ja muuda uue faili nimeks `.env`. Seadista muutujad vastavalt vajadusele.

NB: Muutuja `APP_FILES_PATH` peab olema URI failide kausta. Kui projekt asub domeeni root'is siis sobib vaikimisi väärtus.
Kui projekt asub aga näiteks kaustas `/timeline` siis peab `APP_FILES_PATH` väärtus olema `/timeline/files`.

### Lae alla PHP teegid
``` bash
$ composer install
```

### Lae alla Node.js teegid
``` bash
$ npm install
```

#### Kompileeri kliendipoolsed _asset'id_
Production versiooni loomine
``` bash
$ npm run prod
```

Arenduse ajaks saad käivitada _watcher'i_, et automaatselt muudatused värskendatakse
``` bash
$ npm run dev
```

### Failide, piltide ning puhvrifailide õigused
Kontrolli et PHP'l oleks võimalik kirjutada järgmistesse kaustadesse
* var/cache/
* var/log/
* var/uploads/
* public/files/

### Loo või uuenda andmebaasi
Esiteks kontrolli, et sul oleks loodud andmebaas mille nime seadistasid `.env` failis. Siis saad luua andmebaasi tabelid järgmise käsuga:
``` bash
$ php bin/console db
```

### Loo esimene kasutaja
``` bash
$ php bin/console user:create --admin
```

### Käivita veebiserver
``` bash
$ composer start
```
See käsk käivitab PHP built-in veebiserveri aadressil http://localhost:8080/

## Litsents
[MIT License](LICENSE)
