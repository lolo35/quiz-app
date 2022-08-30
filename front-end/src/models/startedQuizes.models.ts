export interface StartedQuizes {
    id: number,
    started_by: number, //user_id
    finished: boolean, //status of quiz false = started but not finished
    created_at: string,
    updated_at: string,
}