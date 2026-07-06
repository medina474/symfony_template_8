import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["progress"];

    submit(event) {
        event.preventDefault();

        const xhr = new XMLHttpRequest();
        const formData = new FormData(this.element);

        xhr.upload.onprogress = (event) => {
            if (event.lengthComputable) {
                this.progressTarget.value =
                    event.loaded * 100 / event.total;
            }
        };

        xhr.onload = () => {
            console.log("Upload terminé");
        };

        xhr.open(this.element.method, this.element.action);
        xhr.send(formData);
    }
}
