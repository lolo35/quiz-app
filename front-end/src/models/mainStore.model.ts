import { Questions } from './questions.model';

export interface MainStore {
    showUserModal: boolean,
    url: string,
    currentQuestion: number,
    questions: Questions[],
}