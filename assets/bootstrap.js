import { startStimulusApp } from '@symfony/stimulus-bundle';
import ReadMore from 'stimulus-read-more'

const app = startStimulusApp();
app.register('read-more', ReadMore)
