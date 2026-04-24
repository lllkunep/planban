import { useBoard } from '@/composables/useBoard'

export function useRoutes() {
    const { currentBoard } = useBoard()

    const id = (model) => typeof model === 'object' ? model.id : model
    const b  = ()      => currentBoard.value ? id(currentBoard.value) : 0

    return {
        boards: {
            create: () => route('boards.create'),
            store: () => route('boards.store'),
            show: (board) => route('boards.show', id(board)),
            onCard: (card) => route('boards.onCard', [b(), id(card)]),
            update: () => route('boards.update', b()),
            destroy: () => route('boards.destroy', b()),
            edit: () => route('boards.edit', b()),
            removeInvitation: (invitation) => route('boards.removeInvitation', [b(), id(invitation)]),

            columns: {
                store: () => route('boards.columns.store', b()),
                update: (col) => route('boards.columns.update', [b(), id(col)]),
                destroy: (col) => route('boards.columns.destroy', [b(), id(col)]),
                move: (col) => route('boards.columns.move', [b(), id(col)]),
            },

            cards: {
                show: (card) => route('boards.cards.show', [b(), id(card)]),
                store: (col) => route('boards.cards.store', [b(), id(col)]),
                update: (card) => route('boards.cards.update', [b(), id(card)]),
                destroy: (card) => route('boards.cards.destroy', [b(), id(card)]),
                move: (col, card) => route('boards.cards.move', [b(), id(col), id(card)]),
                comments: {
                    store: (card) => route('boards.cards.comments.store', [b(), id(card)]),
                    update: (card, comment) => route('boards.cards.comments.update', [b(), id(card), id(comment)]),
                    destroy: (card, comment) => route('boards.cards.comments.destroy', [b(), id(card), id(comment)]),
                },
            },

            tags: {
                store: () => route('boards.tags.store', b()),
                update: (tag) => route('boards.tags.update', [b(), id(tag)]),
                destroy: (tag) => route('boards.tags.destroy', [b(), id(tag)]),
            },

            users: {
                attach: () => route('boards.attach', b()),
                detach: (user) => route('boards.detach', [b(), id(user)]),
                changeRole: (user) => route('boards.changeRole', [b(), id(user)]),
                setNewOwner: (user) => route('boards.setNewOwner', [b(), id(user)]),
            },
        },

        welcome:   () => route('welcome'),
        dashboard: () => route('dashboard'),

        auth: {
            login:    () => route('login'),
            logout:   () => route('logout'),
            register: () => route('register'),
        },

        password: {
            request: ()        => route('password.request'),
            email:   ()        => route('password.email'),
            reset:   (token)   => route('password.reset', token),
            store:   ()        => route('password.store'),
            update:  ()        => route('password.update'),
            confirm: ()        => route('password.confirm'),
        },

        profile: {
            edit:    () => route('profile.edit'),
            update:  () => route('profile.update'),
            destroy: () => route('profile.destroy'),
        },

        verification: {
            notice: () => route('verification.notice'),
            send:   () => route('verification.send'),
            verify: () => route('verification.verify'),
        },
    }
}
