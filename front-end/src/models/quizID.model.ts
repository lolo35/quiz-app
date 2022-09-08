export interface QuizID {
    id: number,
    started_by: string,
    finished: boolean,
    questions: string,
    created_at: string,
    updated_at: string
}

export interface QuizResult {
    success: boolean,
    points: number,
    total: number,
    percent: number,
    passing: number,
}