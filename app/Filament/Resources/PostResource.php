<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(255)->columnSpan('full'),
                Forms\Components\Textarea::make('content')->required()->maxLength(65553)->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('content')->limit(50),
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('published_at')
                ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')->label('user')->options(\App\Models\User::all()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->form([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('content'),
                    Forms\Components\TextInput::make('user.name'),
                    Forms\Components\TextInput::make('published_at'),
                ]),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('title'),
                Infolists\Components\TextEntry::make('user.name'),
                Infolists\Components\TextEntry::make('published_at'),
                Infolists\Components\TextEntry::make('content')->columnSpanFull(),    
            ]);
        
}
    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
