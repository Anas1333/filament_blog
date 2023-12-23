<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\comments;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('comment')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('user_id')
                    ->label('created_by')
                    ->required()
                    ->default(auth()->id())->disabled()->dehydrated()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\TextColumn::make('comment'),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

            ])
            ->actions([
               
                Tables\Actions\EditAction::make()->authorize(function (comments $comment) {
                    if(auth()->user()->isAdmin()){
                        return true;
                    } else {
                         if($comment->user_id == auth()->user()->id){
                            return true;
                        } else {
                            return false;
                    }
                    }
                   
                }),
                Tables\Actions\DeleteAction::make()->authorize(auth()->user()->isAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->authorize(auth()->user()->isAdmin()),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
