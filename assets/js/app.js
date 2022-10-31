import { async } from 'regenerator-runtime';
import '../css/app.scss';
const form = document.querySelector('form.comment-form');


form.addEventListener('submit', async (e) => {
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

    
}


);

