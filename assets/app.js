import './bootstrap.js';
import './js/main.js';
import { library, dom } from '@fortawesome/fontawesome-svg-core';
import { faInstagram, faFacebook, faTwitter } from '@fortawesome/free-brands-svg-icons';
import * as CookieConsent from "vanilla-cookieconsent";

import "vanilla-cookieconsent/dist/cookieconsent.css";
import './styles/main.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// fontawesome
library.add(faInstagram, faFacebook, faTwitter);
dom.watch();

CookieConsent.run({
    categories: {
        necessary: {
            enabled: true,
            readOnly: true
        },
        analytics: {
            enabled: false,
            readOnly: false
        }
    },
    language: {
        default: 'de',
        translations: {
            de: {
                consentModal: {
                    title: 'Wir verwenden Cookies',
                    description: 'Wir nutzen Cookies, um die Website sicher zu betreiben und das Nutzererlebnis zu verbessern. Optionale Cookies kannst du unten steuern.',
                    acceptAllBtn: 'Alle akzeptieren',
                    acceptNecessaryBtn: 'Nur notwendige',
                    showPreferencesBtn: 'Einstellungen'
                },
                preferencesModal: {
                    title: 'Cookie-Einstellungen',
                    acceptAllBtn: 'Alle akzeptieren',
                    acceptNecessaryBtn: 'Nur notwendige',
                    savePreferencesBtn: 'Auswahl speichern',
                    closeIconLabel: 'Schließen',
                    sections: [
                        {
                            title: 'Über Cookies',
                            description: 'Du kannst deine Einstellungen jederzeit im Footer ändern.'
                        },
                        {
                            title: 'Notwendige Cookies',
                            description: 'Diese Cookies sind für die Grundfunktionen der Website erforderlich und können nicht deaktiviert werden.',
                            linkedCategory: 'necessary'
                        },
                        {
                            title: 'Analyse',
                            description: 'Hilft uns zu verstehen, wie die Website genutzt wird.',
                            linkedCategory: 'analytics'
                        }
                    ]
                }
            },
            ru: {
                consentModal: {
                    title: 'Мы используем cookies',
                    description: 'Мы используем cookies для корректной работы сайта и улучшения сервиса. Необязательные cookies можно настроить.',
                    acceptAllBtn: 'Принять все',
                    acceptNecessaryBtn: 'Только необходимые',
                    showPreferencesBtn: 'Настройки'
                },
                preferencesModal: {
                    title: 'Настройки cookies',
                    acceptAllBtn: 'Принять все',
                    acceptNecessaryBtn: 'Только необходимые',
                    savePreferencesBtn: 'Сохранить выбор',
                    closeIconLabel: 'Закрыть',
                    sections: [
                        {
                            title: 'О cookies',
                            description: 'Изменить выбор можно в футере сайта.'
                        },
                        {
                            title: 'Необходимые cookies',
                            description: 'Эти cookies нужны для работы сайта и не могут быть отключены.',
                            linkedCategory: 'necessary'
                        },
                        {
                            title: 'Аналитика',
                            description: 'Помогает нам понять, как используется сайт.',
                            linkedCategory: 'analytics'
                        }
                    ]
                }
            }
        }
    }
});
