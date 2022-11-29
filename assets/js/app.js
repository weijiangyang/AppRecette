
import { Application } from '@hotwired/stimulus';
import { async } from 'regenerator-runtime';
import '../css/app.scss';

document.addEventListener('DOMContentLoaded',() => {
    new App();
});

class App{
    constructor(){
        this.handleCommentForm();
    }
    
    handleCommentForm(){
        const formComment = document.querySelector('form.comment-form');
        if(null === formComment){
            return;
        }
        formComment.addEventListener('submit', async (e) => {
            e.preventDefault();
            const response = await fetch('/ajax/comments', {
                method: 'POST',
                body: new FormData(e.target)
            })

            if (!response.ok) {
                return;
            }
            const json = await response.json();

            if (json.code === 'COMMENT_ADDED_SUCCESSFULLY') {
                const commentsList = document.querySelector('.comments-list')
                const commentContent = document.querySelector('#comment_content')
                const commentsNumber = document.querySelector("#comment-count")
                commentsList.insertAdjacentHTML('afterbegin', json.message)
                commentsNumber.innerText = json.numberOfComments
                commentContent.value = ''
            }
        })
    }
   
}

        
    

    






