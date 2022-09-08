export interface StartedQuizes {
    id: number,
    quiz_id: number,
    started_by: number, //user_id
    finished: boolean, //status of quiz false = started but not finished
    questions: string,
    created_at: string,
    updated_at: string,
}