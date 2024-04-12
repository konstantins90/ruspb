import { Controller } from "@hotwired/stimulus"

export default class ColapseController extends Controller {
    declare open: boolean
    declare openValue: boolean
    declare contentTarget: HTMLElement
    declare moreTextValue: string
    declare lessTextValue: string

    static targets: string[] = ['content']
    static values = {
        moreText: String,
        lessText: String,
        open: Boolean
    }
  
    connect (): void {
        this.open = false
    }
    
    initialize (): void {
        console.log('INIT')
        console.log(this.openValue)
        this.open = this.openValue
        this.contentTarget.hidden = !this.open
    }
  
    toggle (event: Event): void {
        this.open === false ? this.show(event) : this.hide(event)
    }
  
    show (event: Event): void {
        this.open = true
  
        const target = event.target as HTMLElement
        target.innerHTML = this.lessTextValue
        this.contentTarget.hidden = !this.open
    }
  
    hide (event: Event): void {
        this.open = false
  
        const target = event.target as HTMLElement
        target.innerHTML = this.moreTextValue
        this.contentTarget.hidden = !this.open
    }
}
